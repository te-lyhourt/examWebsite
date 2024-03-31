<template>
    <Dashboard>
        <div class="head">
            <div class="flex justify-between">
                <Button @click="goBack" class="buttonStyle"> < Go Back </Button>
                <Button @click="goBack" class="buttonStyle"> Finish </Button>
            </div>

            <Head title="Test" />
            <div>
                <h1
                    class="mb-4 mt-4 font-medium text-5xl text-gray-700 dark:text-gray-300 grid place-content-center"
                >
                    {{ props.project.name }}
                </h1>
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
                                        ) in project.questions"
                                        @click="changeQuestion(index)"
                                        :class="{
                                            active:
                                                index == currentQuestion.index,
                                        }"
                                    >
                                        <span
                                            v-if="answer[index] == undefined"
                                            :id="'qbtn' + index + 'index'"
                                            style="display: block"
                                            >{{ index + 1 }}</span
                                        >
                                        <span
                                            v-if="answer[index] != undefined"
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
                                                    image/*"
                                        />
                                        <div
                                            class="text-center m-2 rounded-lg"
                                            id="preview"
                                        >
                                            <div
                                                v-if="
                                                    currentQuestion.fileName !=
                                                    ''
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
                                            class="p-2 pt-4 text-xs text-gray-400"
                                        >
                                            <div>
                                                Accept file types: .pdf, .txt,
                                                Microsoft Word, Excel,
                                                Powerpoint and Image file.
                                            </div>
                                            The system does not accept multiple
                                            file upload, you can upload the
                                            files in cloud drive and upload file
                                            content the folder link.
                                        </div>
                                    </div>

                                    <div
                                        v-if="
                                            currentQuestion.question
                                                .fileUpload == 'upAudio'
                                        "
                                    >
                                        <button class="buttonStyle">
                                            Audio
                                        </button>
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

//function
const goBack = () => {
    // Go back to the previous page
    window.history.back();
};
let noQ = true;
const answer = ref([]); //all answer old and new
const submitedAnswer = ref([]); //only submited answer

onMounted(() => {
    if (props.project.questions[0] != undefined) noQ = false;
    const len = props.project.questions.length;

    for (let i = 0; i < len; i++) {

        if (props.project.questions[i].answers[0] != undefined) {
            let propAns = props.project.questions[i].answers[0].answer;
            try {
                propAns = JSON.parse(propAns);
            } catch {}
            answer.value[i] = propAns;
            submitedAnswer.value[i] =propAns;
        }
    }
});
//view test
const props = defineProps({ project: Object });

const currentQuestion = ref({
    index: 0,
    question: props.project.questions[0],
    user: page.props.auth.user.id,
    fileName: "",
    submited: false,
});
var checkboxValue = reactive([]);
var checkboxState = ref([]);
const changeQuestion = (index) => {
    //check if answer need upload
    uploadAnswer();

    //move on to next answer
    currentQuestion.value.index = index;
    currentQuestion.value.question = props.project.questions[index];

    //if json change to json, if not json, it is okay
    try {
        currentQuestion.value.question.options = JSON.parse(
            currentQuestion.value.question.options
        );
    } catch {}

    if (answer.value[index] != undefined) {
        checkboxState.value = [];
        checkboxValue = answer.value[index];
        if (props.project.questions[index].options != null) {
            const len = props.project.questions[index].options.length;
            for (let i = 0; i < len; i++) {
                if (checkboxValue[i] != undefined)
                    checkboxState.value[i] = true;
            }
        }
        if (submitedAnswer.value[index] != undefined) {
            currentQuestion.value.submited = true;
        }
    } else {
        checkboxState.value = [];
        checkboxValue = [];
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
    return props.project.questions.length ==
        parseInt(currentQuestion.value.index) + 1
        ? true
        : false;
});
const nextQ = () => {
    var index = parseInt(currentQuestion.value.index) +1;
    changeQuestion(index);
};
const previousQ = () => {
    const index = parseInt(currentQuestion.value.index) - 1;
    changeQuestion(index);
};

const uploadAnswer = () => {
    let index = parseInt(currentQuestion.value.index);
    var update = false;
    //reset submited
    currentQuestion.value.submited = false;

    //answer already submit, mark as submit
    if(submitedAnswer.value[index]!=undefined){
        currentQuestion.value.submited = true
    }

    //answer change when submit answer and current answer not the same
    if (submitedAnswer.value[index] != answer.value[index] && submitedAnswer.value[index]!=undefined) {
        update = true;
    }

    console.log(answer.value[index])
    console.log(answer.value[index]=="")
    if(answer.value[index]==""){
        answer.value[index]= null
    } 

    const file = fileList[index];
    console.log(file);
    if(file!=undefined) answer.value[index]= "";
    console.log(answer.value[index])
    console.log(answer.value[index]!=null)

    //new question
    if (currentQuestion.value.submited == false && update==false && answer.value[index]!=null) {
        console.log("new question")
        router.post(
            "/answer/add",
            {
                questions_id: currentQuestion.value.question.id,
                user_id: currentQuestion.value.user,
                fileName: currentQuestion.value.fileName,
                answer: JSON.stringify(answer.value[index]),
                file: file,
            },
            {
                onSuccess: (res) => {
                    submitedAnswer.value[index] = answer.value[index];
                },
                onError: (errors) => {
                    errors = Object.values(errors);
                    console.log(errors);
                },
            }
        );
    }
    if(update==true && answer.value[index]!=null) {
        console.log("update question")
        router.patch(
            "/answer/update/" + props.project.questions[index].answers[0].id,
            {
                answer: answer.value[index],
            },
            {
                onSuccess: (res) => {
                    console.log(res);
                    submitedAnswer.value[index] = answer.value[index];
                },
                onError: (errors) => {
                    errors = Object.values(errors);
                    console.log(errors);
                },
            }
        );
    }
    //if user delete text and move on with empty answer
    //we set answer to old answer to prevent submited answer to empty
    if(update==true && answer.value[index]==null) {
        let propAns = props.project.questions[index].answers[0].answer;
        try {
            propAns = JSON.parse(propAns);
        } catch {}
        answer.value[index] = propAns;
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
    answer.value[currentQuestion.value.index] = "";
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
