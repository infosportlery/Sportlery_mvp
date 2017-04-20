<?php

namespace Sportlery\Library\Behaviors;

use RainLab\User\Models\User;
use Sportlery\Library\Classes\FriendshipStatus;
use System\Classes\ModelBehavior;
use Hashids\Hashids as HashidGenerator;

class UserFriendsModel extends ModelBehavior
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

        // Inverse of friends.
        $model->belongsToMany['friendedBy'] = [
            User::class,
            'table' => 'spr_friendships',
            'pivot' => ['status'],
            'timestamps' => true,
            'key' => 'friend_id',
            'otherKey' => 'user_id',
        ];
    }

    /**
     * Returns whether the user has completed their profile details.
     *
     * @return bool
     */
    public function hasCompletedProfile()
    {
        if (!trim($this->model->name) || !trim($this->model->surname)) {
            // Missing first and/or last name.
            return false;
        }

        if (!trim($this->model->street) || !trim($this->model->zip_code) || !trim($this->model->city) || is_null($this->model->latitude) || is_null($this->model->longitude)) {
            // Missing address details.
            return false;
        }

        if ($this->model->favoriteSports()->count() == 0) {
            // No favorite sports specified.
            return false;
        }

        return true;
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
     * Determine whether the model has any accepted friends.
     *
     * @return bool
     */
    public function hasFriends()
    {
        return $this->findFriendships()->exists();
    }

    public function findFriendIdsByHashIds(array $ids)
    {
        /** @var HashidGenerator $hashIds */
        $hashIds = app(HashidGenerator::class);

        $ids = array_map(function ($id) use ($hashIds) {
            $id = $hashIds->decode($id);

            return !is_null($id) ? reset($id) : null;
        }, $ids);

        $ids = array_filter($ids);

        if (!count($ids)) {
            return $this->model->newCollection();
        }

        return $this->getFriendIds($ids);
    }

    /**
     * Get all accepted friends for this model.
     *
     * @param  array $ids
     * @param array $with
     * @return \Illuminate\Database\Eloquent\Collection|\RainLab\User\Models\User[]
     */
    public function listFriends(array $ids = [], array $with = [])
    {
        $friendIds = $this->getFriendIds($ids);

        if (!count($friendIds)) {
            return $this->model->newCollection();
        }

        return $this->model->newQuery()->with($with)->whereIn('id', $friendIds)->get();
    }

    public function paginateFriends($perPage, array $ids = [], array $with = [])
    {
        $friendIds = $this->getFriendIds($ids);

        if (!count($friendIds)) {
            return $this->model->newCollection();
        }

        return $this->model->newQuery()->with($with)->whereIn('id', $friendIds)->paginate($perPage);
    }

    /**
     * Get all accepted friends for this model where the user id is not in the given list.
     *
     * @param  array $ids
     * @param array $with
     * @return \Illuminate\Database\Eloquent\Collection|\RainLab\User\Models\User[]
     */
    public function listFriendsNotIn(array $ids = [], array $with = [])
    {
        $friendIds = $this->getFriendIds($ids, true);

        if (!count($friendIds)) {
            return $this->model->newCollection();
        }

        return $this->model->newQuery()->with($with)->whereIn('id', $friendIds)->get();
    }

    private function getFriendIds(array $ids = [], $inverse = false)
    {
        $friendships = $this->findFriendships($ids, $inverse)->get(['user_id', 'friend_id']);
        $friendIds = [];

        foreach ($friendships as $friendship) {
            if ($friendship->user_id === $this->model->getKey()) {
                $friendIds[] = $friendship->friend_id;
            } else {
                $friendIds[] = $friendship->user_id;
            }
        }

        return $friendIds;
    }

    /**
     * Get a list of all received friend requests that are pending.
     *
     * @return \Illuminate\Database\Eloquent\Collection|User[]
     */
    public function listReceivedFriendRequests()
    {
        return $this->model->friendedBy()->wherePivot('status', FriendshipStatus::PENDING)->get();
    }

    /**
     * Get a list of all received friend requests that are pending.
     *
     * @return \Illuminate\Database\Eloquent\Collection|User[]
     */
    public function countReceivedFriendRequests()
    {
        return $this->model->friendedBy()->wherePivot('status', FriendshipStatus::PENDING)->count();
    }

    /**
     * Get a list of all sent friend requests that are pending.
     *
     * @return \Illuminate\Database\Eloquent\Collection|User[]
     */
    public function listSentFriendRequests()
    {
        return $this->model->friends()->wherePivot('status', FriendshipStatus::PENDING)->get();
    }

    /**
     * Get a list of all blocked users.
     *
     * @return \Illuminate\Database\Eloquent\Collection|User[]
     */
    public function listBlockedFriends()
    {
        return $this->model->friendedBy()->wherePivot('status', FriendshipStatus::BLOCKED)->get();
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

        return $this->findFriendship($otherUser)->where(['status' => FriendshipStatus::ACCEPTED])->delete();
    }

    /**
     * Delete the friendship between the model and the other user.
     *
     * @param  User  $otherUser
     * @return bool
     */
    public function deleteFriendship($otherUser)
    {
        if (!$otherUser) {
            return false;
        }

        return $this->findFriendship($otherUser)->delete();
    }

    /**
     * Accept the friend request from the other user.
     *
     * @param  User  $otherUser
     * @return bool
     */
    public function acceptFriendRequest($otherUser)
    {
        if (!$otherUser) {
            return false;
        }

        return $this->model->friendedBy()->updateExistingPivot($otherUser->getKey(), [
            'status' => FriendshipStatus::ACCEPTED
        ]);
    }

    /**
     * Decline the friend request from the other user.
     *
     * @param  User  $otherUser
     * @return bool
     */
    public function declineFriendRequest($otherUser)
    {
        return $this->deleteFriendship($otherUser);
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

        $updated = $this->model->friendedBy()->updateExistingPivot($otherUser->getKey(), [
            'status' => FriendshipStatus::BLOCKED,
        ]);

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
        return $this->deleteFriendship($otherUser);
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

    /**
     * Start a new pivot query for all accepted friendships for this model.
     *
     * @param  array  $ids
     * @param  bool  $inverse
     * @return \October\Rain\Database\Builder
     */
    protected function findFriendships(array $ids = [], $inverse = false)
    {
        $query = $this->model->friends()->newPivotStatement()
            ->where('status', FriendshipStatus::ACCEPTED)
            ->where(function($q) {
                return $q->orWhere('user_id', $this->model->getKey())
                         ->orWhere('friend_id', $this->model->getKey());
            });

        if (count($ids)) {
            $query->where(function($q) use ($ids, $inverse) {
                if ($inverse) {
                    return $q->whereNotIn('user_id', $ids)->whereNotIn('friend_id', $ids);
                }

                return $q->orWhereIn('user_id', $ids)->orWhereIn('friend_id', $ids);
            });
        }

        return $query;
    }
}
