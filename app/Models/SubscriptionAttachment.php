<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\File;
use Illuminate\Support\Facades\Storage;

class SubscriptionAttachment extends Model
{
    use HasFactory;

    public $table = 'subscriptions_attachments';
    public $timestamps = true;

    public function delete()
    {
        parent::delete();
        Storage::delete($this->file_path);
        return $this;
    }

    public static function deleteBySubscription($subscription_id)
    {
        // Delete all attachments from database and the associated files
        $attachments = self::where('subscription_id', $subscription_id)->get();
        foreach ($attachments as $attachment) {
            $attachment->delete();
        }

        // Disabled for copying the entire folder
        // Delete subscription folder
        // Storage::deleteDirectory('client/1/subscription/' . $subscription_id);

        return true;
    }
}
