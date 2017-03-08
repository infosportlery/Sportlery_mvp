<?php namespace Sportlery\Library\Models;

use RainLab\User\Models\User as RainlabUser;
use Sportlery\Library\Classes\FriendshipStatus;

class User extends RainlabUser
{
    use \Sportlery\Library\Classes\Traits\HashIds;

    protected static function getHashIdPrefixColumn()
    {
        return null;
    }

    public function listFriends()
    {
        return $this->friends()->wherePivot('user_id', $this->getKey())->orWherePivot('friend_id', $this->getKey())->get();
    }

    public function sendFriendRequest($otherUser)
    {
        if (!$otherUser || $this->getFriendshipStatus($otherUser) !== null) {
            // Already some form of friendship.
            return false;
        }

        $this->friends()->attach($otherUser, ['status' => FriendshipStatus::PENDING]);

        return true;
    }

    public function unfriend($otherUser)
    {
        if (!$otherUser) {
            return false;
        }

        return $this->findFriendship($otherUser)->delete();
    }

    public function block($otherUser)
    {
        if (!$otherUser) {
            return false;
        }

        $updated = $this->findFriendship($otherUser)->update(['status' => FriendshipStatus::BLOCKED]);

        if (!$updated) {
            $this->friends()->attach($otherUser, ['status' => FriendshipStatus::BLOCKED]);
        }

        return true;
    }

    public function unblock($otherUser)
    {
        return $this->unfriend($otherUser);
    }

    public function isFriendWith($otherUser)
    {
        return $this->findFriendship($otherUser)->where('status', FriendshipStatus::ACCEPTED)->exists();
    }

    public function getFriendshipStatus($otherUser)
    {
        return $this->findFriendship($otherUser)->pluck('status');
    }

    public function scopeWithoutUser($query, $user)
    {
        return $query->where('id', '!=', $user->getKey());
    }

    protected function findFriendship($otherUser)
    {
        return $this->friends()->newPivotStatement()->where(function($q) use ($otherUser) {
            $q->where(function($query) use ($otherUser) {
                $query->where('user_id', $this->getKey())->where('friend_id', $otherUser->getKey());
            })->orWhere(function($query) use ($otherUser) {
                $query->where('user_id', $otherUser->getKey())->where('friend_id', $this->getKey());
            });
        });
    }
}
