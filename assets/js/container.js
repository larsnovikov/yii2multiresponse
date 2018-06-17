(function() {
    let config = window.widgetConfig;
    console.log(config);
    window.widgetSockets = {};

    $.each(config, function (index, widget) {
        if (widget.base_asset) {
            let url = widget.url;
            let socket = window.widgetSockets[index] = new WebSocket(url);

            socket.onopen = function (e) {
                widget.containers.forEach(function (token) {
                    console.log('register-' + token);
                    socket.send(JSON.stringify({'action': 'registerToken', 'token': token}));
                });
            };

            socket.onmessage = function (e) {
                let response = JSON.parse(e.data);
                let callbackFunction = new Function('response', widget.callback);
                callbackFunction(response);

                const index = widget.containers.indexOf(response.token);
                widget.containers.splice(index, 1);

                if (widget.containers.length === 0) {
                    socket.close();
                }
            };
        }
    });
})();
