<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\File;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Smalot\PdfParser\Parser;
use App\Models\SubscriptionModel;
use App\Models\ProductModel;
use Illuminate\Support\Facades\Response;

class PdfParserController extends Controller
{
    public function parse_pdf(Request $request)
    {
        $result = [
            'product_name' => '',
            'product_price' => '',
            'text' => '',
        ];
        $user_id = Auth::id();
        $file = $request->file('file');
        //appsumo_inv
        //invoice
        //Invoice _ in-45NhFl2KtG_T149EPCXa3S
        $file_name = $file->getClientOriginalName();
        // $file_name = 'appsumo_inv';
        // $file_name = 'Invoice _ in-45NhFl2KtG_T149EPCXa3S';
        $inner_path = "client/1/user/$user_id/tmp";
        $file->storeAs($inner_path, $file_name);
        $data_arr = [];
        if (Storage::disk()->exists("$inner_path/$file_name")) {
            $path = Storage::path("$inner_path/$file_name");
            $parser = new Parser();
            $pdf = $parser->parseFile($path);
            $text = $pdf->getText();
            Storage::disk()->delete("$inner_path/$file_name");
            $result['text'] = $text;
            $product_name = $this->get_product_name($text);
            // echo "<p>$product_name</p>";
            $result['product_name'] = $product_name;
            $product_price = $this->get_product_price($text);
            // echo "<p>$product_price</p>";
            $result['product_price'] = $product_price;
            $product = ProductModel::where('product_name', $product_name)->first();
            if (!$product) {
                $product = ProductModel::find(1);
            }
            $subscriptionData = [
                'id' => 0,
                'brand_id' => $product->id,
                'user_id' => $user_id,
                'product_name' => $product_name,
                'type' => $this->get_html_type($product->pricing_type),
                'price' => $product_price,
                'payment_date' => date('d M Y'),
                'payment_mode' => $this->get_html_payment_method(),
                'billing_cycle' => $this->get_html_billing_cycle($product->billing_frequency, $product->billing_cycle),
                'status' => $this->get_html_status($product->status),
                'recurring' => (int) (!empty($product->billing_frequency) && !empty($product->billing_cycle) && $product->pricing_type != 3),
            ];
            $data_arr[]= $subscriptionData;
            // $subscription_id = SubscriptionModel::create($subscriptionData);
            // echo "<p>$subscription_id</p>";
            /*$data = $pdf->getPages()[0]->getDataTm();
            $dataByY = [];
            foreach ($data as $value) {
                $dataByY[$value[0][5]][]= $value[1];
            }
            foreach ($dataByY as &$value) {
                $value = implode(' ', $value);
            }
            unset($value);
            foreach ($dataByY as $value) {
                // echo "<p>$value</p>";
            }*/
        }
        
        $response = [
            'draw' => 1,
            'iTotalRecords' => 1,
            'iTotalDisplayRecords' => 1,
            'aaData' => $data_arr
        ];


        return response()->json($response);

        /*return Response::json([
            'status' => 'success',
            'message' => 'File parsed successfully',
            'data' => $result,
        ], 200);*/
    }

    public function get_product_name($text)
    {
        preg_match('/Provided by:\s*(([[:alpha:]]+\s+)+)/', $text, $matches);
        if (isset($matches[1])) {
            $product_name = $matches[1];
        } else {
            preg_match('/Invoice Details\s*(([[:alpha:]]+\s+)+)Invoice/', $text, $matches);
            if (isset($matches[1])) {
                $product_name = $matches[1];
            } else {
                preg_match('/In vo ic e  f ro m\s+([\w\s]+)\./', $text, $matches);
                if (isset($matches[1])) {
                    $product_name = $matches[1];
                    $product_name = explode('  ', $product_name);
                    foreach ($product_name as &$value) {
                        $value = str_replace(' ', '', $value);
                    }
                    unset($value);
                    $product_name = implode(' ', $product_name);
                } else {
                    $product_name = '';
                }
            }
        }
        return $product_name;
    }

    public function get_product_price($text)
    {
        preg_match('/Total Paid USD (\d+(\.\d+)?)/', $text, $matches);
        if (isset($matches[1])) {
            $product_price = $matches[1];
        } else {
            preg_match('/Total paid \([\w\s]+\)\s+\$(\d+(\.\d+)?)/', $text, $matches);
            if (isset($matches[1])) {
                $product_price = $matches[1];
            } else {
                preg_match('/T otal Left\s+\$(\d+(\.\d+)?)/', $text, $matches);
                if (isset($matches[1])) {
                    $product_price = $matches[1];
                } else {
                    $product_price = '';
                }
            }
        }
        return $product_price;
    }

    protected function get_html_type($type) {
        $output = '<select name="type" class="form-control">';
        foreach (table('subscription.type') as $key => $val) {
            $val_lang = __($val);
            if ($type == $key) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            $output .= "<option value='$key' $selected>$val_lang</option>";
        }
        $output .= '</select>';
        return $output;
    }

    protected function get_html_payment_method() {
        $output = "<select name='type' class='form-control'>";
        $select_lang = __('Select');
        $output .= "<option selected='' disabled='' value='' style='display: none;'>$select_lang</option>";
        foreach (lib()->user->payment_methods as $val) {
            if (lib()->user->default->payment_mode_id == $val->id) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            $output .= "<option value='$val->id' $selected>$val->name</option>";
        }
        $output .= '</select>';
        return $output;
    }

    protected function get_html_billing_cycle($billing_frequency, $billing_cycle) {
        $every_lang = __('Every');
        $set_billing_frequency_lang = __('Set Billing Frequency');
        $set_billing_cycle_lang = __('Set Billing Cycle');
        $frequency_options = '';
        for ($i = 1; $i <= 40; $i++) {
            if ($billing_frequency == $i) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            $frequency_options .= "<option value='$i' $selected>".__($i)."</option>";
        }
        $subscription_options = '';
        foreach (table('subscription.cycle') as $key => $val) {
            $val_lang = __($val);
            if ($billing_cycle == $key) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            $subscription_options .= "<option value='$key' $selected>$val_lang</option>";
        }
        $output = <<<HTML
            <div class="input-group">
                <div class="input-group-prepend">
                    <label for="subscription_add_billing_frequency" class="input-group-text">$every_lang</label>
                </div>
                <select name="billing_frequency" id="subscription_add_billing_frequency" class="form-control pr-0" required data-toggle="tooltip" data-placement="top" title="$set_billing_frequency_lang">
                    $frequency_options
                </select>
                <select name="billing_cycle" id="subscription_add_billing_cycle" class="form-control" required data-toggle="tooltip" data-placement="top" title="$set_billing_cycle_lang">
                    $subscription_options
                </select>
            </div>
        HTML;
        return $output;
    }

    protected function get_html_status($status) {
        $title_lang = __('If Active your Subs/Lifetime is tracked , Draft can not be track your Subs/Lifetime');
        $active_lang = __('Active');
        $draft_lang = __('Draft');
        $output = <<<HTML
            <div class="header_toggle_btn_container toggle btn btn-warning mr-3" id="subscription_add_status_toggle_container" data-toggle="toggle">
                <input type="checkbox" name="status" id="subscription_add_status" value="$status" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" checked>
                <div class="toggle-group" data-toggle="tooltip" data-placement="left" title="$title_lang">
                    <label class="btn btn-success toggle-on">$active_lang</label>
                    <label class="btn btn-warning toggle-off">$draft_lang</label>
                    <span class="toggle-handle btn btn-light"></span>
                </div>
            </div>
        HTML;
        return $output;
    }
}
