import m from 'mithril';

console.log('Welcome to Laravel Drydock!');

let LastMessage = {
    list: () => {
        return m.request({method: "GET", url: "/api/web/last-message"});
    }
};

class MessageMonitor {

    protected interval;

    static createFactory(milliseconds : number) {
        let messageMonitor = new MessageMonitor(milliseconds);
        return () => { return messageMonitor };
    }

    //
    //

    constructor(milliseconds : number) {
        this.interval = setInterval(this.check, milliseconds);
    }

    check() {
        console.log("Checking for a processed message!");
    }

    view(ctrl) {
        console.log(ctrl);
    }

}

//
//

let messageMonitorFactory = MessageMonitor.createFactory(3000);

m.mount(document.getElementById('last-message'), {
    controller: messageMonitorFactory,
    view: messageMonitorFactory
});
