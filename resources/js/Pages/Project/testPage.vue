<template>
    <Dashboard>
        <div class="head">
            <div class="flex justify-between">
                <Button @click="goBack" class="buttonStyle"> < Go Back </Button>
                <!-- <Button @click="goBack" class="buttonStyle"> Finish </Button> -->
            </div>

            <Head title="Test" />
            <div>
                <h1
                    class="mb-4 mt-4 font-medium text-5xl text-gray-700 dark:text-gray-300 grid place-content-center"
                >
                    {{ props.project.name }}
                </h1>
            </div>
            <div
                @click="showDescription = !showDescription"
                class="p-3 rounded-xl mr-2 my-3"
                style="border: white solid 1px; cursor: pointer"
                v-if="props.project.description"
            >
                <div class="flex ">
                    <span v-if="showDescription" class="mr-5">⌃</span>
                    <span v-if="!showDescription" class="mr-5">⌄</span>
                    <span class="text-lg text-bold">Description : </span>
                </div>

                <p
                    class="text-wrap break-words mx-3 my-3"
                    v-if="showDescription"
                >
                    {{ props.project.description }}
                </p>
            </div>
        </div>
        <div>
            <div id="User" class="" v-if="!noQ">
                <div>
                    <div class="flex justify-between">
                        <!-- question list-->
                        <div
                            class="taskList text-center rounded-xl min-w-[140px]"
                        >
                            <div class="my-3">Question List</div>
                            <div class="my-3">Progress : {{ project.answersCount + " / " +  project.totalQuestions }}</div>
                            <div
                                class="p-[10px] max-h-[200px] overflow-auto buttonList"
                            >
                                <div
                                    class="grid grid-cols-3 gap-1 place-content-center"
                                >
                                    <button
                                        class="h-[35px] w-[30px] m-1 buttonStyle selectBtn"
                                        v-for="(
                                            question, index
                                        ) in questionList"
                                        @click="changeQuestion(index)"
                                        :class="{
                                            active:
                                                index == currentQuestion.index,
                                        }"
                                    >
                                        <span
                                            v-if="
                                                !questionList[index]
                                                    .hasAnswer
                                            "
                                            :id="'qbtn' + index + 'index'"
                                            style="display: block"
                                            >{{ index + 1 }}</span
                                        >
                                        <span
                                            v-if="
                                                questionList[index]
                                                    .hasAnswer
                                            "
                                            :id="'qbtn' + index + 'check'"
                                            >✔️</span
                                        >
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- question content -->
                        <div
                            class="w-full mx-2 p-2 border rounded-md flex flex-col items-center"
                        >
                            <div class="w-full mx-2 p-2 rounded-md">
                                {{ currentQuestion.question.description }}
                            </div>
                            <div class="answer mx-2 w-full">
                                <div
                                    v-if="
                                        currentQuestion.question.fileUpload !=
                                            'none' &&
                                        currentQuestion.question.fileUpload !=
                                            null
                                    "
                                    class="border rounded-lg my-2 p-2"
                                >
                                    <div
                                        v-if="
                                            currentQuestion.question
                                                .fileUpload == 'upFile'
                                        "
                                    >
                                        <!-- :value="fileList[currentQuestion.index].name" -->
                                        <label
                                            for="image_uploads"
                                            @click="triggerfileBtn"
                                            class="buttonStyle"
                                            >Choose File</label
                                        >
                                        <input
                                            @change="fileChange($event)"
                                            id="uploadFile"
                                            v-show="false"
                                            type="file"
                                            accept="application/msword, 
                                                    application/vnd.ms-excel, 
                                                    application/vnd.ms-powerpoint,
                                                    text/plain, 
                                                    application/pdf, 
                                                    image/jpeg,
                                                    image/png"
                                        />
                                        <div
                                            class="text-center m-2 rounded-lg"
                                            id="preview"
                                        >
                                            <div
                                                v-if="
                                                    currentQuestion.fileName  &&
                                                    !questionList[
                                                        currentQuestion.index
                                                    ].hasUrl
                                                "
                                            >
                                                Upload file name:
                                                {{ currentQuestion.fileName }}
                                            </div>

                                            <div
                                                v-if="
                                                    currentQuestion.fileName ==
                                                    ''
                                                "
                                            >
                                                No files currently selected for
                                                upload
                                            </div>
                                        </div>
                                        <div
                                            class="flex justify-center"
                                            v-if="
                                                questionList[
                                                    currentQuestion.index
                                                ].hasUrl
                                            "
                                        >
                                            <a
                                                class="py-3"
                                                :href="
                                                    questionList[
                                                        currentQuestion.index
                                                    ].answers[0].url
                                                "
                                                >Uploaded:
                                                <span
                                                    class="underline underline-offset-1"
                                                    >{{
                                                        questionList[
                                                            currentQuestion
                                                                .index
                                                        ].answers[0].fileName
                                                    }}</span
                                                >
                                            </a>
                                        </div>
                                        <div
                                            class="p-2 pt-4 text-xs text-gray-400"
                                        >
                                            <div>
                                                Accept file types: .pdf, .txt,
                                                Microsoft Word, Excel,
                                                Powerpoint and jpeg or png
                                                image.
                                            </div>
                                            The system does not accept multiple
                                            file upload, you can upload the
                                            files in cloud drive and upload file
                                            content the folder link.
                                        </div>
                                    </div>

                                    <div
                                        class="flex justify-center items-center flex-col"
                                        v-if="
                                            currentQuestion.question
                                                .fileUpload == 'upAudio'
                                        "
                                    >
                                        <div>
                                            <button
                                                class="buttonStyle !text-base items-center"
                                                @click="startRecording"
                                                v-if="stopButton"
                                            >
                                                Start Recording 🔊
                                            </button>
                                            <button
                                                class="text-base bg-red-700 text-white hover:bg-white hover:text-red-700 p-3 rounded-lg items-center"
                                                @click="stopRecording"
                                                v-if="!stopButton"
                                            >
                                                Stop Recording 🔈
                                            </button>
                                        </div>

                                        <li
                                            style="list-style: none"
                                            class="pl-[10px]"
                                        >
                                            <div
                                                class="center"
                                                v-if="
                                                    audioList[
                                                        currentQuestion.index
                                                    ]
                                                "
                                            >
                                                <audio
                                                    class="mt-3"
                                                    :src="
                                                        audioList[
                                                            currentQuestion
                                                                .index
                                                        ].url
                                                    "
                                                    controls
                                                ></audio>
                                            </div>
                                        </li>
                                        <div
                                            class="flex justify-center"
                                            v-if="
                                                questionList[
                                                    currentQuestion.index
                                                ].hasUrl
                                            "
                                        >
                                            <a
                                                class="py-3"
                                                :href="
                                                    questionList[
                                                        currentQuestion.index
                                                    ].answers[0].url
                                                "
                                                >Uploaded:
                                                <span
                                                    class="underline underline-offset-1"
                                                    >{{
                                                        questionList[
                                                            currentQuestion
                                                                .index
                                                        ].answers[0].fileName
                                                    }}</span
                                                >
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <Textarea
                                    v-if="
                                        currentQuestion.question.type ==
                                        'default'
                                    "
                                    placeholder="write your answer"
                                    class="text_scroll border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white text-black focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    v-model="answer[currentQuestion.index]"
                                />

                                <div
                                    v-if="
                                        currentQuestion.question.type ==
                                        'checkbox'
                                    "
                                >
                                    <div
                                        v-for="(
                                            option, index
                                        ) in currentQuestion.question.options"
                                    >
                                        <input
                                            type="checkbox"
                                            id="checkbox_answer"
                                            class="!bg-black m-2 checkbox"
                                            v-model="checkboxState[index]"
                                            @change="
                                                checkbox_answer(
                                                    $event,
                                                    index,
                                                    option
                                                )
                                            "
                                        />
                                        <label>{{ option }}</label>
                                    </div>
                                </div>
                                <div
                                    v-if="
                                        currentQuestion.question.type ==
                                        'multiple'
                                    "
                                >
                                    <div
                                        v-for="option in currentQuestion
                                            .question.options"
                                    >
                                        <input
                                            class="!bg-black m-2"
                                            type="radio"
                                            v-model="
                                                answer[currentQuestion.index]
                                            "
                                            :value="option"
                                        />
                                        <label>{{ option }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m-2 flex justify-between">
                        <Button
                            class="buttonStyle"
                            @click="previousQ"
                            :disabled="disablePre"
                        >
                            < Back
                        </Button>
                        <Button
                            class="buttonStyle"
                            @click="uploadAnswer($event)"
                            >Submit this question</Button
                        >
                        <Button
                            class="buttonStyle"
                            @click="nextQ"
                            :disabled="disableNext"
                            >Next ></Button
                        >
                    </div>
                </div>
            </div>
        </div>
    </Dashboard>
</template>
<script setup>
//import
import Dashboard from "@/Pages/Dashboard.vue";
import $ from "jquery";
import { Head, useForm, usePage, router } from "@inertiajs/vue3";
import { computed, ref, onMounted, reactive } from "vue";
import { Button } from "@/components/ui/button";
import { Textarea } from "@/components/ui/textarea";

//data
const page = usePage();
const showDescription = ref(false);
//function
const goBack = () => {
    // Go back to the previous page
    window.history.back();
};
let noQ = true;
const answer = ref([]); //all answer old and new
const submitedAnswer = ref([]); //only submited answer
const currentQuestion = ref({
    index: 0,
    question: "",
    user: page.props.auth.user.id,
    fileName: "",
    submited: false,
});

const audioList = ref([]);

onMounted(() => {
    getAnswer();

    //make sure the first current question is first question
    currentQuestion.value.question = questionList.value[0];
    if (questionList.value[0] != undefined) noQ = false;
    try {
        currentQuestion.value.question.options = JSON.parse(
            currentQuestion.value.question.options
        );
    } catch {}
});
//get answer
const questionList = ref();
const getAnswer = () => {
    const len = props.project.questions.length;
    //props cannot change value so we clone it:
    questionList.value = JSON.parse(JSON.stringify(props.project.questions));
    //loop all question
    for (let i = 0; i < len; i++) {
        //has answer
        if (props.project.questions[i].answers[0]) {
            let answerList = props.project.questions[i].answers;
            let repeatIndex =  props.project.questions[i].repeatIndex;
            let matchAnswer = answerList.filter(answer => answer.repeatIndex === repeatIndex);
            
            if(matchAnswer[0]!=undefined) questionList.value[i].answers[0] = matchAnswer[0];
            else questionList.value[i].answers[0] = [];
            let answerText = questionList.value[i].answers[0].answer;
            try {
                answerText = JSON.parse(answerText);
            } catch {}
            
            //if has answer text
            if (answerText) {
                questionList.value[i].hasAnswer = true;
                answer.value[i] = answerText;
                submitedAnswer.value[i] = answerText;
            }

            //no answer text but has url
            if (questionList.value[i].answers[0].url) {
                questionList.value[i].hasUrl = true;
                questionList.value[i].hasAnswer = true;
            }
        }
    }
};

//view test
const props = defineProps({ project: Object });

var checkboxValue = reactive([]);
var checkboxState = ref([]);
const changeQuestion = (index) => {
    //prevent freshing current question
    //if current question index and index not the sam move to new question
    if (index != currentQuestion.value.index) {
        //move on to next answer
        currentQuestion.value.index = index;
        currentQuestion.value.question = questionList.value[index];
        currentQuestion.value.fileName = "";

        if (questionList.value[index].answers[0]) {
            currentQuestion.value.fileName =
            questionList.value[index].answers[0].fileName;
        }
        if (fileList[index]) {
            currentQuestion.value.fileName = fileList[index].name;
        }
        //if json change to json, if not json, it is okay
        try {
            currentQuestion.value.question.options = JSON.parse(
                currentQuestion.value.question.options
            );
        } catch {}

        if (answer.value[index]) {
            checkboxState.value = [];
            checkboxValue = answer.value[index];
            if (questionList.value[index].options != null) {
                const len = questionList.value[index].options.length;
                for (let i = 0; i < len; i++) {
                    if (checkboxValue[i]) checkboxState.value[i] = true;
                }
            }
            if (submitedAnswer.value[index]) {
                currentQuestion.value.submited = true;
            }
        } else {
            checkboxState.value = [];
            checkboxValue = [];
        }
    }
};

const checkbox_answer = (evt, index, option) => {
    if (evt.target.checked) {
        checkboxValue[index] = option;
    } else checkboxValue[index] = "";
    answer.value[currentQuestion.value.index] = checkboxValue;
};
const disablePre = computed(() => {
    return currentQuestion.value.index == 0 ? true : false;
});
const disableNext = computed(() => {
    return questionList.value.length == parseInt(currentQuestion.value.index) + 1
        ? true
        : false;
});

const nextQ = () => {
    var index = parseInt(currentQuestion.value.index) + 1;
    changeQuestion(index);
};
const previousQ = () => {
    const index = parseInt(currentQuestion.value.index) - 1;
    changeQuestion(index);
};

const uploadAnswer = (evt) => {
    let index = parseInt(currentQuestion.value.index);
    //prevent submit empty answer
    if (
        (answer.value[index] != null && answer.value[index] != "") ||
        fileList[index]
    ) {
        evt.target.disabled = true;
        evt.target.textContent = "Uploading...";

        //new question
        if (!currentQuestion.value.question.hasAnswer) {
            console.log("new question");
            let newAnswer = answer.value[index];
            if (typeof newAnswer !== "string") {
                try {
                    newAnswer = JSON.parse(newAnswer);
                    newAnswer = newAnswer.filter((value) => value !== null);
                } catch {}
            }
            router.post(
                "/answer/add",
                {
                    project_id: props.project.id,
                    questions_id: currentQuestion.value.question.id,
                    user_id: currentQuestion.value.user,
                    user_email: page.props.auth.user.email,
                    fileName: currentQuestion.value.fileName,
                    answer: JSON.stringify(newAnswer),
                    file: fileList[index],
                    repeatIndex: currentQuestion.value.question.repeatIndex,
                },
                {
                    onSuccess: () => {
                        evt.target.disabled = false;
                        evt.target.textContent = "Submit this question";
                        getAnswer();
                        currentQuestion.value.question.hasAnswer =true
                    },
                    onError: (errors) => {
                        errors = Object.values(errors);
                        console.log(errors);
                    },
                }
            );
        }

        // update question
        if (currentQuestion.value.question.hasAnswer) {
            console.log("update question");

            let updateFile = false;
            if (fileList[index]) {
                console.log("change file");
                updateFile = true;
            }

            router.post(
                "/answer/update/" + questionList.value[index].answers[0].id,
                {
                    project_id: props.project.id,
                    questions_id: currentQuestion.value.question.id,
                    user_id: currentQuestion.value.user,
                    user_email: page.props.auth.user.email,
                    answer: JSON.stringify(answer.value[index]),
                    updateFile: updateFile,
                    file: fileList[index],
                    oldPath: questionList.value[index].answers[0].filePath,
                },
                {
                    onSuccess: (res) => {
                        evt.target.disabled = false;
                        evt.target.textContent = "Submit this question";
                        // currentQuestion.value.question.hasAnswer = true;
                        getAnswer();
                        currentQuestion.value.question.hasAnswer =true;
                    },
                    onError: (errors) => {
                        errors = Object.values(errors);
                        console.log(errors);
                    },
                }
            );
        }
    }
};

const triggerfileBtn = () => {
    const fileBtn = $("#uploadFile");
    fileBtn.trigger("click");
};
const fileList = [];
const fileChange = (evt) => {
    var files = evt.target.files || evt.dataTransfer.files;
    if (!files.length) return;
    fileList[currentQuestion.value.index] = files[0];
    // answer.value[currentQuestion.value.index] = "";
    currentQuestion.value.fileName = fileList[currentQuestion.value.index].name;
};

//webkitURL is deprecated but nevertheless
URL = window.URL || window.webkitURL;
var gumStream; //stream from getUserMedia()
var recorder; //WebAudioRecorder object
var input; //MediaStreamAudioSourceNode  we'll be recording
// shim for AudioContext when it's not avb.
var AudioContext = window.AudioContext || window.webkitAudioContext;

const stopButton = ref(true);
var encodingType = "wav";
var encodeAfterRecord = true;

const startRecording = () => {
    // We'll us a simple constraints object, for more advanced features see https://blog.addpipe.com/audio-constraints-getusermedia/
    var constraints = {
        audio: true,
        video: false,
    };

    /* We're using the standard promise based getUserMedia() https://developer.mozilla.org/en-US/docs/Web/API/MediaDevices/getUserMedia */
    navigator.mediaDevices
        .getUserMedia(constraints)
        .then(function (stream) {
            //assign to gumStream for later use
            gumStream = stream;
            // when to encode
            var audioContext = new AudioContext();
            /* use the stream */
            input = audioContext.createMediaStreamSource(stream);

            //stop the input from playing back through the speakers
            //input.connect(audioContext.destination)

            recorder = new WebAudioRecorder(input, {
                encoding: encodingType,
                numChannels: 2,
                onEncoderLoading: async function (recorder, encoding) {},
                onEncoderLoaded: async function (recorder, encoding) {},
            });

            recorder.onComplete = function (recorder, blob) {
                createDownloadLink(blob, recorder.encoding);
            };
            recorder.setOptions({
                timeLimit: 120,
                encodeAfterRecord: encodeAfterRecord,
                ogg: {
                    quality: 0.5,
                },
                mp3: {
                    bitRate: 160,
                },
            });
            //start the recording process
            recorder.startRecording();
        })
        .catch(function (err) {
            //enable the record button if getUSerMedia() fails
            stopButton.value = true;
        });

    //disable the record button
    stopButton.value = false;
};

const stopRecording = () => {
    //stop microphone access
    gumStream.getAudioTracks()[0].stop();

    //disable the stop button
    stopButton.value = true;

    //tell the recorder to finish the recording (stop recording + encode the recorded audio)
    recorder.finishRecording();
};
//record audio

const createDownloadLink = (blob, encoding) => {
    var url = URL.createObjectURL(blob);
    var audioName =
        // neptun.value +
        // "_" +
        currentQuestion.value.question.description +
        "_" +
        (new Date().valueOf() + ".").replace(/\s/g, "") +
        encoding;
    audioList.value[currentQuestion.value.index] = {
        // text: textToRead.value,
        url: url,
        audioName: audioName,
        blob: blob,
        saveState: "save",
        disabled: false,
        // audioTextNumber: currentText,
    };
    //add file to answer list

    //blob is pretty much file without this 2 property: name and lastModified
    var file = new File([blob], audioName, { lastModified: new Date() });
    fileList[currentQuestion.value.index] = file;
    // answer.value[currentQuestion.value.index] = "";
    currentQuestion.value.fileName = fileList[currentQuestion.value.index].name;
};
</script>
<style scoped>
@import "../../../css/button.css";
@import "../../../css/checkbox.css";
@import "../../../css/textinput.css";

.taskList {
    width: 180px;
    min-height: 200px;
    border: white solid 1px;
}

.buttonList::-webkit-scrollbar,
.options::-webkit-scrollbar {
    width: 0.5rem;
    height: 0.5rem;
}
.buttonList::-webkit-scrollbar-thumb,
.options::-webkit-scrollbar-thumb {
    border-radius: calc(0.5rem - 4px);
    background-color: hsl(240 5% 64.9% / 0.3);
}
.buttonList ::-webkit-scrollbar-track,
.options::-webkit-scrollbar-track {
    background-color: hsl(240 3.7% 15.9%);
}
.active {
    background-color: rgb(55 65 81 / var(--tw-bg-opacity));
    color: rgb(255 255 255 / var(--tw-text-opacity));
}
</style>
