<template>
    <Dashboard>
        <div>
            <div>
                <Head title="Project" />
                <h2
                    class="mb-4 mt-4 font-medium text-5xl text-gray-700 dark:text-gray-300 grid place-content-center"
                >
                    Project List
                </h2>
            </div>
            <div class="flex w-full justify-between">
                <Button
                    v-if="!roleUser"
                    variant="outline"
                    class="buttonStyle ml-2"
                    @click="deleteProject"
                >
                    Delete Project
                </Button>
                <div class="flex justify-end mr-4">
                    <Dialog v-if="!roleUser">
                        <DialogTrigger as-child>
                            <Button variant="outline" class="buttonStyle">
                                Create New Project
                            </Button>
                        </DialogTrigger>
                        <DialogContent
                            class="max-w-[650px] bg-white dark:bg-gray-800 text-xs dark:text-white text-gray-800"
                        >
                            <DialogHeader class="p-6 pt-0">
                                <DialogTitle
                                    class="text-gray-800 dark:text-white text-center text-3xl"
                                    >Create New Project</DialogTitle
                                >
                            </DialogHeader>
                            <CardContent>
                                <form @submit.prevent="submit">
                                    <div class="grid items-center w-full gap-4">
                                        <div class="flex flex-col space-y-1.5">
                                            <span
                                                class="block font-medium text-sm text-gray-800 dark:text-white"
                                                >Project Name
                                                <span style="color: red"
                                                    >*</span
                                                ></span
                                            >
                                            <TextInput
                                                class="mt-1 block w-full"
                                                v-model="form.name"
                                                required

                                            />
                                            <InputError
                                                class="mt-2"
                                                :message="form.errors.name"
                                            />
                                        </div>
                                        <div class="flex flex-col space-y-1.5">
                                            <span
                                                class="block font-medium text-sm text-gray-800 dark:text-white"
                                                >Description
                                            </span>
                                            <textarea
                                                class="text_scroll border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white text-black focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                                v-model="form.description"
                                            />
                                            <InputError
                                                class="mt-2"
                                                :message="
                                                    form.errors.description
                                                "
                                            />
                                        </div>
                                        <div class="flex flex-col space-y-1.5">
                                            <span
                                                class="block font-medium text-sm text-gray-800 dark:text-white"
                                                >Number of Reapeat for each
                                                question
                                            </span>
                                            <h6 class="text-gray-500">
                                                By default each question appear
                                                1 time. if set to 2, when
                                                create a question, the same
                                                question will create 2 times.
                                            </h6>
                                            <TextInput
                                                type="number"
                                                min="1"
                                                max="10"
                                                class="mt-1 block w-full"
                                                v-model="form.repeatNum"
                                            />
                                            <InputError
                                                class="mt-2"
                                                :message="form.errors.repeatNum"
                                            />
                                        </div>
                                        <div class="flex flex-col space-y-1.5">
                                            <span
                                                class="block font-medium text-sm text-gray-800 dark:text-white"
                                                >Number of question for each
                                                student
                                            </span>
                                            <h6 class="text-gray-500">
                                                By default each student get all
                                                the questions in project. if
                                                question number set to 3, each
                                                student will get an offset of 3
                                                questions from the
                                                questions list.
                                            </h6>
                                            <TextInput
                                                type="number"
                                                min="1"
                                                class="mt-1 block w-full"
                                                v-model="form.questNum"
                                            />
                                            <InputError
                                                class="mt-2"
                                                :message="form.errors.questNum"
                                            />
                                        </div>
                                    </div>
                                    <div class="mt-10">
                                        <h6 class="text-gray-500">
                                            For example: if repeat question set
                                            to 2, A student will get: question
                                            1 x 2 times, question 2 x 1 times.
                                            another student may get: question
                                            2 x 1 times, question 3 x 2 times.
                                        </h6>
                                    </div>
                                    <div class="flex justify-end mt-6">
                                        <DialogFooter class="sm:justify-start">
                                            <DialogClose as-child>
                                                <Button
                                                    id="closeBtn"
                                                    type="button"
                                                    variant="secondary"
                                                    class="ml-6 bg-gray-800 dark:bg-gray-200 text-xs text-white dark:text-gray-800 hover:bg-gray-700 dark:hover:bg-white hover:text-white dark:hover:text-gray-800"
                                                >
                                                    Close
                                                </Button>
                                            </DialogClose>
                                            <Button
                                                type="submit"
                                                :disabled="
                                                    form.processing ||
                                                    dataFilled
                                                "
                                                variant="secondary"
                                                class="ml-6 bg-gray-800 dark:bg-gray-200 text-xs text-white dark:text-gray-800 hover:bg-gray-700 dark:hover:bg-white hover:text-white dark:hover:text-gray-800"
                                            >
                                                ADD PROJECT
                                            </Button>
                                        </DialogFooter>
                                    </div>
                                </form>
                            </CardContent>
                        </DialogContent>
                    </Dialog>
                </div>
            </div>

            <div class="mt-5">
                <Table>
                    <!-- <TableCaption>A list of your recent invoices.</TableCaption> -->
                    <TableHeader>
                        <TableRow>
                            <TableHead class="text-center w-[75px]">
                                <input
                                    v-if="!roleUser"
                                    type="checkbox"
                                    class="checkbox"
                                    id="checkPR"
                                    v-model="checkAll"
                                    @click="selectAll"
                                    v-show="showAll"
                                />
                                <div
                                    class="flex justify-center"
                                    v-if="!showAll"
                                >
                                    <button
                                        for="box"
                                        class="checkbox px-[6px] h-[18px] flex items-center font-bold"
                                        @click="unSelectAll"
                                    >
                                        <div class="mb-[2px]">-</div>
                                    </button>
                                </div>
                            </TableHead>
                            <TableHead>PROJECT Name</TableHead>
                            <TableHead class="text-center">
                                Num Question
                            </TableHead>
                            <TableHead class="text-center">
                                Num Repeat
                            </TableHead>
                            <TableHead class="text-center">
                                Created At
                            </TableHead>
                            
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="project in projects" :key="project.id">
                            <TableCell class="text-center">
                                <input
                                    v-if="!roleUser"
                                    type="checkbox"
                                    class="checkbox"
                                    id="checkChile"
                                    @change="
                                        updateSelectList(project.id, $event)
                                    "
                                />
                            </TableCell>
                            <TableCell >{{ project.name }}</TableCell>
                            <TableCell class="text-center">{{ project.questNum }}</TableCell>
                            <TableCell class="text-center">{{ project.repeatNum }}</TableCell>
                            <TableCell class="text-center">
                                {{
                                    moment(project.created_at).format(
                                        "YYYY-MM-DD HH:mm"
                                    )
                                }}
                            </TableCell>
                            <TableCell class="text-center">
                                <Button
                                    variant="outline"
                                    class="buttonStyle"
                                    @click="GoDetail(project.id)"
                                >
                                    DETAIL
                                </Button>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
    </Dashboard>
</template>
<script setup>
//Imports
import $ from "jquery";
import moment from "moment";
import { computed, ref, onMounted } from "vue";
import Dashboard from "@/Pages/Dashboard.vue";
import { Head, useForm, usePage, router } from "@inertiajs/vue3";
import { Button } from "@/components/ui/button";
import { CardContent } from "@/components/ui/card";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";

import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table";
import {
    Dialog,
    DialogContent,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
    DialogClose,
} from "@/components/ui/dialog";
import Textarea from "@/components/ui/textarea/Textarea.vue";
//Uses

//Refs

const roleUser = ref(false);
// const roleSAdmin= ref(false);
const page = usePage();
const form = useForm({
    name: "",
    description: "",
    questNum: "",
    repeatNum: 1,
    created_by: parseInt(page.props.auth.user.id),
});

//Props $ Emit
const props = defineProps({ projects: Object });

//Computed
const dataFilled = computed(() => {
    if (form.name.length > 0) {
        return false;
    } else return true;
});

onMounted(() => {
    //check user role
    const role = JSON.parse(page.props.auth.user.role)[0];
    if (role == "user") roleUser.value = true;
    else roleUser.value = false;

    // if(role=='system admin') roleSAdmin.value = true;
    // else roleSAdmin.value = false;
});

const submit = () => {
    form.post(route("project.add"), {
        onSuccess: () => {
            form.reset();
            $("#closeBtn").trigger("click");
        },
    });
};

const listLen = props.projects.length;
const checkAll = ref(false);
const showAll = ref(true);
const selectAll = () => {
    if (checkAll.value) {
        $('input[id="checkChile"]').trigger("click");
    } else {
        $('input[id="checkChile"]').trigger("click");
    }
    showAll.value = true;
};
const unSelectAll = () => {
    $('input[id="checkChile"]').prop("checked", false);
    selectList.value = [];
    showAll.value = true;
    $('input[id="checkPR"]').prop("checked", false);
};
const selectList = ref([]);
const updateSelectList = (id, event) => {
    //if groups is select
    if (event.target.checked) {
        if (!selectList.value.includes(id)) selectList.value.push(id);
    } else {
        const index = selectList.value.indexOf(id);
        if (index !== -1) {
            selectList.value.splice(index, 1);
        }
    }
    if (selectList.value.length == 0) {
        $('input[id="checkPR"]').prop("checked", false);
        showAll.value = true;
    }
    if (selectList.value.length > 0 && selectList.value.length <= listLen)
        showAll.value = false;
    if (selectList.value.length == listLen) {
        showAll.value = true;
        $('input[id="checkPR"]').prop("checked", true);
    }
};
const deleteProject = () => {
    if(selectList.value.length!=0){
        if (confirm("Press OK to delete selected project!") == true) {
            router.delete("/project/delete", {
                data: {
                    projects: selectList.value,
                },
            });
            selectList.value = [];
            $('input[id="checkPR"]').prop("checked", false);
            showAll.value = true;
        }
    }
    
};
const GoDetail = (id) => {
    if (!roleUser.value) {
        router.get("/project/detail/" + id);
    } else router.get("/project/test/" + id);
};
</script>
<style scoped>
@import "../../../css/button.css";
@import "../../../css/checkbox.css";
</style>
