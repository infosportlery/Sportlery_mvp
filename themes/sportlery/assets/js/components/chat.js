$(function() {

    $('#new-chat-modal').on('show.bs.modal', function() {
        $.request('onFetchFriends');
    });

    const $chatForm = $('#chat-form');

    if (!$chatForm.length) {
        return;
    }

    const $message = $('#message');
    const $messages = $('#chat-messages');
    const POLL_RATE = 10000; // 10 seconds.
    const $loadMoreChats = $('#load-more-chats');
    const $emptyConversation = $('.empty-conversation');
    let polledSince = (new Date()).toISOString();

    $message.on('input', function() {
        $chatForm.find('[type=submit]').prop('disabled', $.trim($(this).val()).length == 0);
    });

    const handleSuccess = function (data) {
        let shouldScroll = $messages.scrollTop() >= ($messages.prop('scrollHeight') - $messages.height()) - 15;

        if (data.length !== 0) {
            $emptyConversation.remove();
        }

        this.success(data);

        if (shouldScroll) {
            $messages.scrollTop($messages.prop('scrollHeight'));
        }
    };

    $messages.scrollTop($messages.prop('scrollHeight'));

    $chatForm.on('submit', function (e) {
        e.preventDefault();

        $chatForm.find('[type=submit]').prop('disabled', true);
        $chatForm.request('onAddMessage', {
            success(data) {
                handleSuccess.call(this, data);
            }
        });
        $message.val('');

        return false;
    });

    $loadMoreChats.on('click', function (e) {
        e.preventDefault();

        const firstMessageTime = $messages.find('.chat-message:first time').attr('datetime');
        $loadMoreChats.prop('disabled', true);

        $.request('onLoadMoreMessages', {
            complete() {
                $loadMoreChats.prop('disabled', false);
            },
            success(data) {
                handleSuccess.call(this, data);

                if (data.length === 0) {
                    $loadMoreChats.closest('.load-more-wrapper').remove();
                    return;
                }

                const oldTop = $loadMoreChats.position().top;

                $loadMoreChats.closest('.load-more-wrapper').prependTo($messages);
                $messages.scrollTop(oldTop);
            },
            data: {
                until: firstMessageTime
            }
        });
    });

    setInterval(function() {
        $.request('onCheckNewMessages', {
            success: function(data) {
                handleSuccess.call(this, data);
            },
            data: {
                since: polledSince
            }
        });

        polledSince = (new Date()).toISOString();
    }, POLL_RATE);

});
