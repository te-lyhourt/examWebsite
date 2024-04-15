<template>
    <Dashboard>
        <div>
            <div>
                <Button @click="goBack" class="buttonStyle"> < Go Back</Button>
                <Head title="Group Detail" />
                <h2
                    class="mb-4 mt-4 font-medium text-5xl text-gray-700 dark:text-gray-300 grid place-content-center"
                >
                    User Progress
                </h2>
                <h5
                    class="mb-4 mt- font-medium text-2xl text-gray-700 dark:text-gray-300 grid place-content-center"
                >
                    User: {{ props.project.user.email }}
                </h5>
            </div>
            <div class="flex justify-between">
                <div class="flex justify-end mr-4"></div>
            </div>

            <div class="mt-5">
                <div
                    class="rounded-xl min-h-20"
                    style="border: white solid 1px"
                    v-for="(question, index) in project.questions"
                >
                    <div class="p-2">
                        <span>Question {{ index+1 }} : </span
                        >{{ question.description }}
                    </div>

                    <div class="p-2" v-for="(anw, index) in answer[index]">
                        <span>Answer {{ index+1 }} : </span> <span>{{ anw }}</span>
                    </div>
                    <div class="p-2" v-for="(url, index) in url_list[index]">
                        <span>File {{ index + 1 }} : </span>
                        <a
                            class="py-3 underline underline-offset-1"
                            :href="url.url"
                            >{{ url.name }}</a
                        >
                    </div>
                </div>
            </div>
        </div>
    </Dashboard>
</template>

<script setup>
//Imports

import Dashboard from "@/Pages/Dashboard.vue";

import { Head, useForm, usePage, router } from "@inertiajs/vue3";
import { computed, onMounted, reactive, ref } from "vue";

import { Button } from "@/components/ui/button";

const answer = ref([]);
const url_list = ref([]);
onMounted(()=>{
    const len = props.project.questions.length;

    for (let i = 0; i < len; i++) {
        if (props.project.questions[i].answers[0] != undefined) {
            let answerArray=[];
            let urlArray = [];
            props.project.questions[i].answers.forEach((anw) => {
                let text = anw.answer;

                try {
                    text = JSON.parse(text);
                    text = text.filter(item => item !== null);
                } catch {}
                if(text!=null) answerArray.push(text);

                if(anw.url){

                    urlArray.push({
                        url:anw.url,
                        name: anw.fileName,
                    });
                }
            });
            answer.value[i] = answerArray;
            url_list.value[i] =urlArray;
        }
    }
})

//Props $ Emit
const props = defineProps({ project: Object });

const goBack = () => {
    // Go back to the previous page
    window.history.back();
};
const goAnswer = (user_id,project_id)=>{
    let url = "/group/progress_detail/" + user_id+"/"+project_id;
    router.get(url);
}
</script>
<style scoped>
@import "../../../css/button.css";
@import "../../../css/checkbox.css";

.text_scroll::-webkit-scrollbar {
    width: 0.5rem;
    height: 0.5rem;
}
.text_scroll::-webkit-scrollbar-thumb {
    border-radius: calc(0.5rem - 4px);
    background-color: hsl(240 5% 64.9% / 0.3);
}
.text_scroll::-webkit-scrollbar-track {
    background-color: hsl(240 3.7% 15.9%);
}
</style>
