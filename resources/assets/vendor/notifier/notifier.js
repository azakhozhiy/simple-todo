(function () {
    var template = {
        system: '<div id="notifier-system"><div id="notifier-system__icon" class="material-icons"></div><div id="notifier-system__text"></div><div id="notifier-system__close" class="material-icons">' +
          '<i class="fa fa-times" aria-hidden="true"></i></div></div>',

        clientWrap: '<div id="notifier-client"><div id="notifier-client__list"></div></div>',

        clientItem: '<div class="notifier-client__item" data-notifier-el="item"><div class="notifier-client__wrapper" data-notifier-el="wrapper"><div class="notifier-client__pic material-icons"  data-notifier-el="pic"></div><div class="notifier-client__title"  data-notifier-el="title"></div><div class="notifier-client__content" data-notifier-el="content"></div></div><div class="notifier-client__close material-icons" data-notifier-el="close">&#xE5CD;</div></div></div>',
        
        clientButton: '<div class="notifier-client__button" data-notifier-el="button"></div>'
    };

    var notifier = {
        systemClassType: '',
        systemState: false,
        systemTimer: null
    };

    var $notifierClient = $(template.clientWrap),
        $notifierClientList = $notifierClient.find('#notifier-client__list');

    $notifierClient.appendTo('body');

    /*
     @param bgColor
     @param iconColor
     @param data.icon
     @param data.img
     @param data.title
     @param data.content
     @param button
     @param buttonOnClick()
     */
    notifier.pushClient = function (data) {
        var $item = $(template.clientItem),
            $wrapper = $item.find('[data-notifier-el="wrapper"]'),
            $pic = $item.find('[data-notifier-el="pic"]'),
            $title = $item.find('[data-notifier-el="title"]'),
            $content = $item.find('[data-notifier-el="content"]'),
            $close = $item.find('[data-notifier-el="close"]');

        if (data.img) {
            $pic.css({
                'background-image': 'url(' + data.img + ')'
            });
        }
        else if (data.icon) {
            $pic.html(data.icon);
            if (data.iconColor) {
                $pic.css({
                    'color': data.iconColor
                });
            }
        }

        if (data.bgColor) {
            $pic.css({
                'background-color': data.bgColor
            });
        }

        $title.text(data.title);
        $content.html(data.content);

        if (data.button) {
            $button = $(template.clientButton);
            $button.text(data.button);

            $button.appendTo($wrapper);
            $button.on('click', function () {
                if (typeof data.buttonOnClick === 'function') {
                    data.buttonOnClick();
                }
            });
        }

        if (data.noTimer !== true) {
            $item.closeTimer = setTimeout(function () {
                notifier.closeClient($item);
            }, 6000);
        }


        $notifierClientList.append($item);
        $item.fadeIn(400);
        $item.addClass('active');

        $close.on('click', function () {
            notifier.closeClient($item);
        });

        return $item;
    }

    notifier.closeClient = function ($item) {
        clearTimeout($item.closeTimer);

        $item.fadeOut(400, function () {
            $item.remove();
        });
    }

    var $notifierSystem = $(template.system),
        $notifierSystemIcon = $notifierSystem.find('#notifier-system__icon'),
        $notifierSystemText = $notifierSystem.find('#notifier-system__text'),
        $notifierSystemClose = $notifierSystem.find('#notifier-system__close');

    $notifierSystem.appendTo('body');

    $notifierSystemClose.on('click', function () {
        notifier.closeSystem();
    });

    /*
     @param data.type
     @param data.text
     @param data.icon
     @param data.bgColor
     */
    notifier.callSystem = function (data) {
        if (notifier.systemState) {
            notifier.closeSystem(function () {
                notifier.openSystem(data);
            });
        }
        else {
            notifier.openSystem(data);
        }
    }

    notifier._stylizeSystem = function (data) {
        var type = (data.type) ? data.type : 'info',
            icon = (data.icon) ? data.icon : (type == 'info') ? '&#xE88E;' : (type == 'done') ? '&#xE876;' : (type == 'error') ? '&#xE868;' : '&#xE88E;',
            bgColor = (data.bgColor) ? data.bgColor : false;

        $notifierSystem.removeClass(notifier.systemClassType);

        notifier.systemClassType = type;
        $notifierSystem.addClass(notifier.systemClassType);

        $notifierSystemIcon.html(icon);

        if (bgColor) {
            $notifierSystem.css({
                'background-color': bgColor
            });
        }
        else {
            $notifierSystem.css({
                'background-color': ''
            });
        }
    }

    notifier.openSystem = function (data) {
        notifier._stylizeSystem(data);

        $notifierSystemText.text(data.text);
        $notifierSystem.fadeIn(400);
        $notifierSystem.addClass('active');

        notifier.systemState = true;

        notifier.systemTimer = setTimeout(function () {
            notifier.closeSystem();
        }, 5000);
    };

    notifier.closeSystem = function (cb) {
        if (notifier.systemState === true) {
            $notifierSystem.fadeOut(400, function () {
                $notifierSystemText.text('');
                $notifierSystem.removeClass('active');

                if (typeof cb === 'function')
                    cb();
            });

            notifier.systemState = false;

            if (notifier.systemTimer !== null) {
                clearTimeout(notifier.systemTimer);
                notifier.systemTimer = null;
            }
        }
    }

    $.notifier = notifier;

})();
