<?php

namespace Spatie\LivewireComments\Support;

use Spatie\Comments\Support\Config as BaseConfig;
use Spatie\LivewireComments\Policies\CommentPolicy;

use Spatie\LivewireComments\Policies\ReactionPolicy;

class Config extends BaseConfig
{
    /** @return class-string<CommentPolicy> */
    public static function getCommentPolicyName(): string
    {
        return config('comments.policies.comment', CommentPolicy::class);
    }

    /** @return class-string<ReactionPolicy> */
    public static function getReactionPolicyName(): string
    {
        return config('comments.policies.reaction', ReactionPolicy::class);
    }

    public static function editor(): string
    {
        return config('comments.ui.editor', 'comments::editors.easymde');
    }

    public static function showAvatars(): bool
    {
        return config('comments.ui.show_avatars', true);
    }
}
