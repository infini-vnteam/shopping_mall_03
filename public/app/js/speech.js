$(function () {
    const speech = new Vue({
        el: '#speech2text',
        data: {
            say: '',
            is_record: false,
            show_result: false,
            label: '',
            recognition: new window.webkitSpeechRecognition,
            lang: 'en-US',
        },
        methods: {
            record: function () {
                if (!this.is_record && this.say === '') {
                    this.is_record = true;
                    this.show_result = true;
                    this.recognition.lang = this.lang;
                    this.recognition.interimResults = false;
                    this.recognition.maxAlternatives = 5;

                    this.speechLabelChange();

                    this.recognition.start();

                    setTimeout(function () {
                        if (speech.is_record) {
                            speech.recognition.stop();
                            speech.stopRecognition();
                        }
                    }, 4000);
                    this.recognition.onresult = function (event) {
                        speech.stopRecognition();
                        speech.showResult(event.results[0][0].transcript);
                    };
                }
            },
            speechLabelChange: function () {
                this.say = '';
                this.label = 'Speech now';
                setTimeout(function () {
                    speech.label = 'Listening...';
                }, 1200);
            },
            showResult: function (say) {
                this.is_record = false;
                this.label = '';
                this.say = say;
                setTimeout(function () {
                    if (!speech.is_record) {
                        speech.action(say);
                    }
                }, 1000);
            },
            stopRecognition: function () {
                this.is_record = false;
                setTimeout(function () {
                    if (speech.say === '' && !speech.is_record) {
                        speech.show_result = false;
                    }
                }, 200);
            },
            action: function (say) {
                let arr = say.split(' ');
                switch (arr[0]) {
                    case 'check':
                        if (arr[1] === 'out') {
                            location.href = '/cart';
                        }
                        break;
                    case 'checkout':
                        location.href = '/cart';
                        break;
                    case 'go':
                        switch (arr[arr.length - 1]) {
                            case 'home':
                                location.href = '/';
                                speech.say = 'Go to home page';
                                break;
                        }
                        break;
                    case 'tracking':
                        location.href = '/order/track';
                        break;
                    default:
                        Search.keyword = say;
                        Search.search(say);
                }
                setTimeout(function () {
                    speech.say = '';
                    if (!speech.is_record) {
                        speech.show_result = false;
                    }
                }, 500);
            },
        },
    });
});