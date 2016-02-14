import m from 'mithril';

console.log('Welcome to Laravel Drydock!');

class Messages {

    public static last() {
        return m.request({method: "GET", url: "/api/web/last-message"});
    }

}

class MessageMonitor {

    protected message;

    protected milliseconds;

    protected interval;

    public constructor(milliseconds : number) {
        this.milliseconds = milliseconds;
    }

    //
    //

    public controller = () => {

        this.interval = setInterval(this.check, this.milliseconds);

    };

    public check = () => {
        this.message = Messages.last();
    };

    //
    //

    // Note: I strongly suspect there could be a better way to check whether data was retrieved. Looking for a mithril expert to chime in!
    public view = (ctrl) => {

        if(this.message == undefined)
            return null;

        let message = this.message();
        if(message.length) {

            this.message = null;

            return m("div", [
                m("h4", {}, "Your message is done processing!"),
                m("div", {"class": "notice"}, message),
            ]);

        }
        else
            return m("div");

    };

}

m.mount(document.getElementById('last-message'), new MessageMonitor(4000));