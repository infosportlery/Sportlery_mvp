<?php

namespace Sportlery\Library\Behaviors;

use RainLab\User\Models\User;
use System\Classes\ModelBehavior;
use Hashids\Hashids as HashidGenerator;

class UserModel extends ModelBehavior
{
    /**
     * Initialize the friendable model behavior.
     *
     * @param  \October\Rain\Database\Model  $model
     */
    public function __construct($model)
    {
        parent::__construct($model);

        $model->belongsToMany['friends'] = [
            User::class,
            'table' => 'spr_friendships',
            'pivot' => ['status'],
            'timestamps' => true,
            'key' => 'user_id',
            'otherKey' => 'friend_id',
        ];
    }

    /**
     * Find a model by hash id.
     *
     * @param  string  $hashId
     * @return \October\Rain\Database\Model|null
     */
    public function findByHashId($hashId)
    {
        $hashId = app(HashidGenerator::class)->decode($hashId);

        if (empty($hashId)) {
            return null;
        }

        return $this->model->find(reset($hashId));
    }

    /**
     * Get the model's hash id.
     *
     * @return string
     */
    public function getHashId()
    {
        return app(HashidGenerator::class)->encode($this->model->getKey());
    }

    /**
     * Scope the given query to exclude the current user.
     *
     * @param  \October\Rain\Database\Builder  $query
     * @param  User  $user
     * @return \October\Rain\Database\Builder
     */
    public function scopeWithoutUser($query, $user)
    {
        return $query->where('id', '!=', $user->getKey());
    }

    /**
     * Get all friends for this model.
     *
     * @return \Illuminate\Database\Eloquent\Collection|User[]
     */
    public function listFriends()
    {
        return $this->model->friends()->wherePivot('user_id', $this->model->getKey())
                               ->orWherePivot('friend_id', $this->model->getKey())
                               ->get();
    }

    /**
     * Send a friend request to the given other user, if there is none yet.
     *
     * @param  User  $otherUser
     * @return bool
     */
    public function sendFriendRequest($otherUser)
    {
        if (!$otherUser || $this->getFriendshipStatus($otherUser) !== null) {
            // Already some form of friendship.
            return false;
        }

        $this->model->friends()->attach($otherUser, ['status' => FriendshipStatus::PENDING]);

        return true;
    }

    /**
     * Unfriend the given other user.
     *
     * @param  User  $otherUser
     * @return bool
     */
    public function unfriend($otherUser)
    {
        if (!$otherUser) {
            return false;
        }

        return $this->findFriendship($otherUser)->delete();
    }

    /**
     * Block the given other user.
     *
     * @param  User  $otherUser
     * @return bool
     */
    public function block($otherUser)
    {
        if (!$otherUser) {
            return false;
        }

        $updated = $this->findFriendship($otherUser)->update(['status' => FriendshipStatus::BLOCKED]);

        if (!$updated) {
            $this->model->friends()->attach($otherUser, ['status' => FriendshipStatus::BLOCKED]);
        }

        return true;
    }

    /**
     * Unblock the given other user.
     *
     * @param  User  $otherUser
     * @return bool
     */
    public function unblock($otherUser)
    {
        return $this->unfriend($otherUser);
    }

    /**
     * Determine whether the user is friends with the given other user.
     *
     * @param  User  $otherUser
     * @return bool
     */
    public function isFriendWith($otherUser)
    {
        return $this->findFriendship($otherUser)->where('status', FriendshipStatus::ACCEPTED)->exists();
    }

    /**
     * Get the friendship status between the user and the given other user.
     *
     * @param  User  $otherUser
     * @return int
     */
    public function getFriendshipStatus($otherUser)
    {
        return $this->findFriendship($otherUser)->pluck('status');
    }

    /**
     * Start a new query on the friendship (pivot) table between the user and the given other user.
     *
     * @param  User  $otherUser
     * @return \October\Rain\Database\Builder
     */
    protected function findFriendship($otherUser)
    {
        return $this->model->friends()->newPivotStatement()->where(function($q) use ($otherUser) {
            $q->where(function($query) use ($otherUser) {
                $query->where('user_id', $this->model->getKey())->where('friend_id', $otherUser->getKey());
            })->orWhere(function($query) use ($otherUser) {
                $query->where('user_id', $otherUser->getKey())->where('friend_id', $this->model->getKey());
            });
        });
    }
}
