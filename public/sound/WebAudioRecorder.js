(function (n) {
    var e = function () {
        var i = arguments[0]
            , t = [].slice.call(arguments, 1);
        for (var n = 0; n < t.length; ++n) {
            var r = t[n];
            for (Object.key in r) {
                var o = r[Object.key];
                i[Object.key] = typeof o === "object" ? e(typeof i[Object.key] === "object" ? i[Object.key] : {}, o) : o
            }
        }
        return i
    };
    var o = {
        wav: "WebAudioRecorderWav.min.js",
        ogg: "WebAudioRecorderOgg.min.js",
        mp3: "WebAudioRecorderMp3.min.js"
    };
    var t = {
        // workerDir: "/",
        numChannels: 2,
        encoding: "wav",
        options: {
            timeLimit: 300,
            encodeAfterRecord: false,
            progressInterval: 1e3,
            bufferSize: undefined,
            wav: {
                mimeType: "audio/wav"
            },
            ogg: {
                mimeType: "audio/ogg",
                quality: .5
            },
            mp3: {
                mimeType: "audio/mpeg",
                bitRate: 160
            }
        }
    };
    var i = function (i, n) {
        e(this, t, n || {});
        this.context = i.context;
        if (this.context.createScriptProcessor == null)
            this.context.createScriptProcessor = this.context.createJavaScriptNode;
        this.input = this.context.createGain();
        i.connect(this.input);
        this.buffer = [];
        this.initWorker()
    };
    e(i.prototype, {
        isRecording: function () {
            return this.processor != null
        },
        setEncoding: function (e) {
            if (this.isRecording())
                this.error("setEncoding: cannot set encoding during recording");
            else if (this.encoding !== e) {
                this.encoding = e;
                this.initWorker()
            }
        },
        setOptions: function (i) {
            if (this.isRecording())
                this.error("setOptions: cannot set options during recording");
            else {
                e(this.options, i);
                this.worker.postMessage({
                    command: "options",
                    options: this.options
                })
            }
        },
        startRecording: function () {
            if (this.isRecording())
                this.error("startRecording: previous recording is running");
            else {
                var i = this.numChannels
                    , e = this.buffer
                    , n = this.worker;
                this.processor = this.context.createScriptProcessor(this.options.bufferSize, this.numChannels, this.numChannels);
                this.input.connect(this.processor);
                this.processor.connect(this.context.destination);
                this.processor.onaudioprocess = function (t) {
                    for (var o = 0; o < i; ++o)
                        e[o] = t.inputBuffer.getChannelData(o);
                    n.postMessage({
                        command: "record",
                        buffer: e
                    })
                }
                    ;
                this.worker.postMessage({
                    command: "start",
                    bufferSize: this.processor.bufferSize
                });
                this.startTime = Date.now()
            }
        },
        recordingTime: function () {
            return this.isRecording() ? (Date.now() - this.startTime) * .001 : null
        },
        cancelRecording: function () {
            if (this.isRecording()) {
                this.input.disconnect();
                this.processor.disconnect();
                delete this.processor;
                this.worker.postMessage({
                    command: "cancel"
                })
            } else
                this.error("cancelRecording: no recording is running")
        },
        finishRecording: function () {
            if (this.isRecording()) {
                this.input.disconnect();
                this.processor.disconnect();
                delete this.processor;
                this.worker.postMessage({
                    command: "finish"
                })
            } else
                this.error("finishRecording: no recording is running")
        },
        cancelEncoding: function () {
            if (this.options.encodeAfterRecord)
                if (this.isRecording())
                    this.error("cancelEncoding: recording is not finished");
                else {
                    this.onEncodingCanceled(this);
                    this.initWorker()
                }
            else
                this.error("cancelEncoding: invalid method call")
        },
        initWorker: function () {
            if (this.worker != null)
                this.worker.terminate();
            this.onEncoderLoading(this, this.encoding);
            const url = import.meta.url;
            const parts = url.split('/');
            if (parts.length == 5) {
                parts.pop();
                parts.pop();
            }
            else parts.pop();

            const modifiedUrl = parts.join('/');
            function worker_function() {
                (function (t) {
                    var s = Math.min
                        , r = Math.max;
                    var n = function (t, s, n) {
                        var r = n.length;
                        for (var e = 0; e < r; ++e)
                            t.setUint8(s + e, n.charCodeAt(e))
                    };
                    var e = function (e, n) {
                        this.sampleRate = e;
                        this.numChannels = n;
                        this.numSamples = 0;
                        this.dataViews = []
                    };
                    e.prototype.encode = function (o) {
                        var e = o[0].length
                            , i = this.numChannels
                            , f = new DataView(new ArrayBuffer(e * i * 2))
                            , u = 0;
                        for (var n = 0; n < e; ++n)
                            for (var t = 0; t < i; ++t) {
                                var a = o[t][n] * 32767;
                                f.setInt16(u, a < 0 ? r(a, -32768) : s(a, 32767), true);
                                u += 2
                            }
                        this.dataViews.push(f);
                        this.numSamples += e
                    }
                        ;
                    e.prototype.finish = function (r) {
                        var t = this.numChannels * this.numSamples * 2
                            , e = new DataView(new ArrayBuffer(44));
                        n(e, 0, "RIFF");
                        e.setUint32(4, 36 + t, true);
                        n(e, 8, "WAVE");
                        n(e, 12, "fmt ");
                        e.setUint32(16, 16, true);
                        e.setUint16(20, 1, true);
                        e.setUint16(22, this.numChannels, true);
                        e.setUint32(24, this.sampleRate, true);
                        e.setUint32(28, this.sampleRate * 4, true);
                        e.setUint16(32, this.numChannels * 2, true);
                        e.setUint16(34, 16, true);
                        n(e, 36, "data");
                        e.setUint32(40, t, true);
                        this.dataViews.unshift(e);
                        var s = new Blob(this.dataViews, {
                            type: "audio/wav"
                        });
                        this.cleanup();
                        return s
                    }
                        ;
                    e.prototype.cancel = e.prototype.cleanup = function () {
                        delete this.dataViews
                    }
                        ;
                    t.WavAudioEncoder = e
                }
                )(self);
                var sampleRate = 44100
                    , numChannels = 2
                    , options = undefined
                    , maxBuffers = undefined
                    , encoder = undefined
                    , recBuffers = undefined
                    , bufferCount = 0;
                function error(e) {
                    self.postMessage({
                        command: "error",
                        message: "wav: " + e
                    })
                }
                function init(e) {
                    sampleRate = e.config.sampleRate;
                    numChannels = e.config.numChannels;
                    options = e.options
                }
                function setOptions(e) {
                    if (encoder || recBuffers)
                        error("cannot set options during recording");
                    else
                        options = e
                }
                function start(e) {
                    maxBuffers = Math.ceil(options.timeLimit * sampleRate / e);
                    if (options.encodeAfterRecord)
                        recBuffers = [];
                    else
                        encoder = new WavAudioEncoder(sampleRate, numChannels)
                }
                function record(e) {
                    if (bufferCount++ < maxBuffers)
                        if (encoder)
                            encoder.encode(e);
                        else
                            recBuffers.push(e);
                    else
                        self.postMessage({
                            command: "timeout"
                        })
                }
                function postProgress(e) {
                    self.postMessage({
                        command: "progress",
                        progress: e
                    })
                }
                function finish() {
                    if (recBuffers) {
                        postProgress(0);
                        encoder = new WavAudioEncoder(sampleRate, numChannels);
                        var e = Date.now() + options.progressInterval;
                        while (recBuffers.length > 0) {
                            encoder.encode(recBuffers.shift());
                            var n = Date.now();
                            if (n > e) {
                                postProgress((bufferCount - recBuffers.length) / bufferCount);
                                e = n + options.progressInterval
                            }
                        }
                        postProgress(1)
                    }
                    self.postMessage({
                        command: "complete",
                        blob: encoder.finish(options.wav.mimeType)
                    });
                    cleanup()
                }
                function cleanup() {
                    encoder = recBuffers = undefined;
                    bufferCount = 0
                }
                self.onmessage = function (n) {
                    var e = n.data;
                    switch (e.command) {
                        case "init":
                            init(e);
                            break;
                        case "options":
                            setOptions(e.options);
                            break;
                        case "start":
                            start(e.bufferSize);
                            break;
                        case "record":
                            record(e.buffer);
                            break;
                        case "finish":
                            finish();
                            break;
                        case "cancel":
                            cleanup()
                    }
                }
                    ;
                self.postMessage({
                    command: "loaded"
                });
            }
            if (window != self)
                worker_function();
            try {
                this.worker = new Worker(URL.createObjectURL(new Blob(["(" + worker_function.toString() + ")()"], { type: 'text/javascript' })));
            } catch (error) {
                console.log(error)
            }

            var e = this;
            this.worker.onmessage = function (n) {
                var i = n.data;
                switch (i.command) {
                    case "loaded":
                        e.onEncoderLoaded(e, e.encoding);
                        break;
                    case "timeout":
                        e.onTimeout(e);
                        break;
                    case "progress":
                        e.onEncodingProgress(e, i.progress);
                        break;
                    case "complete":
                        e.onComplete(e, i.blob);
                        break;
                    case "error":
                        e.error(i.message)
                }
            }
                ;
            this.worker.postMessage({
                command: "init",
                config: {
                    sampleRate: this.context.sampleRate,
                    numChannels: this.numChannels
                },
                options: this.options
            })
        },
        error: function (e) {
            this.onError(this, "WebAudioRecorder.min.js:" + e)
        },
        onEncoderLoading: function (e, i) { },
        onEncoderLoaded: function (e, i) { },
        onTimeout: function (e) {
            e.finishRecording()
        },
        onEncodingProgress: function (e, i) { },
        onEncodingCanceled: function (e) { },
        onComplete: function (e, i) {
            e.onError(e, "WebAudioRecorder.min.js: You must override .onComplete event")
        },
        onError: function (i, e) {
            console.log(e)
        }
    });
    n.WebAudioRecorder = i
}
)(window);