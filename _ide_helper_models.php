<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */

namespace App\Models{
    /**
     * @property int $id
     * @property int $sender_id
     * @property int $receiver_id
     * @property float $amount
     * @property string|null $description
     * @property bool $is_successful
     * @property string|null $failure_reason
     * @property \Illuminate\Support\Carbon|null $completed_at
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\User $receiver
     * @property-read \App\Models\User $sender
     *
     * @method static \Illuminate\Database\Eloquent\Builder|Transaction newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Transaction newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Transaction query()
     * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereAmount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCompletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereDescription($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereFailureReason($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereIsSuccessful($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereReceiverId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereSenderId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereUpdatedAt($value)
     *
     * @mixin \Eloquent
     */
    #[\AllowDynamicProperties]
    class IdeHelperTransaction
    {
    }
}

namespace App\Models{
    /**
     * @property int $id
     * @property string $name
     * @property int $type
     * @property string $identifier
     * @property string $email
     * @property \Illuminate\Support\Carbon|null $email_verified_at
     * @property mixed $password
     * @property float $balance
     * @property string|null $remember_token
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
     * @property-read int|null $notifications_count
     *
     * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|User query()
     * @method static \Illuminate\Database\Eloquent\Builder|User whereBalance($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereIdentifier($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
     *
     * @mixin \Eloquent
     */
    #[\AllowDynamicProperties]
    class IdeHelperUser
    {
    }
}
