@if (!App\Models\SubscriptionModel::get_tags($subscription->id)->count())
    <span>N/A</span>
@else
    @foreach(App\Models\SubscriptionModel::get_tags($subscription->id) as $tag)
        <span class="badge badge-secondary">{{ $tag->name }}</span>
    @endforeach
@endif