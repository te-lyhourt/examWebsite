<template>
    <Dashboard>
        <div class="head">
            <Button @click="goBack" class="buttonStyle"> < Go Back </Button>
            <Head title="Project Detail" />
            <div>
                <h1
                    class="mb-4 mt-4 font-medium text-5xl text-gray-700 dark:text-gray-300 grid place-content-center"
                >
                    {{ props.project.name }}
                </h1>
            </div>
        </div>

        <!-- Tab  -->
        <div class="tab" v-if="!roleUser">
            <button
                class="tablinks"
                @click="openCity($event, 'Question')"
                id="default"
            >
                Question
            </button>
            <button class="tablinks" @click="openCity($event, 'Group')">
                Group
            </button>
            <button class="tablinks" @click="openCity($event, 'Admin')">
                Admin
            </button>
        </div>

        <!-- Tab content-->

        <!-- if admin -->
        <div
            id="Question"
            class="tabcontent"
            v-if="tabe == 'Question' && !roleUser"
        >
            <div>
                <div class="flex w-full justify-between">
                    <Button
                        variant="outline"
                        class="buttonStyle ml-2"
                        @click="removeQuestion"
                    >
                        Remove Question
                    </Button>

                    <div class="flex justify-end mr-4" v-if="!roleUser">
                        <button
                            class="buttonStyle flex items-center mx-2"
                            @click="goTest(project.id)"
                        >
                            View Questions as User
                        </button>
                        <Dialog>
                            <DialogTrigger as-child>
                                <Button variant="outline" class="buttonStyle">
                                    Add Question To Project
                                </Button>
                            </DialogTrigger>

                            <DialogContent
                                class="max-w-[40rem] bg-white dark:bg-gray-800 text-xs dark:text-white text-gray-800"
                            >
                                <DialogHeader class="p-6 pt-0">
                                    <DialogTitle
                                        class="text-gray-800 dark:text-white text-center text-3xl"
                                        >Add Question To Project</DialogTitle
                                    >
                                </DialogHeader>
                                <CardContent>
                                    <form @submit.prevent="addQuestion">
                                        <div
                                            class="grid items-center w-full gap-4"
                                        >
                                            <div
                                                class="flex flex-col space-y-1.5"
                                            >
                                                <span
                                                    class="block font-medium text-sm text-gray-800 dark:text-white"
                                                >
                                                    Task description
                                                    <span style="color: red"
                                                        >*</span
                                                    >
                                                </span>
                                                <Textarea
                                                    placeholder="Add description"
                                                    class="text_scroll border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white text-black focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                                    v-model="
                                                        questionForm.description
                                                    "
                                                    autofocus
                                                />
                                            </div>
                                            <div
                                                class="flex flex-col space-y-1.5"
                                            >
                                                <span
                                                    class="block font-medium text-sm text-gray-800 dark:text-white"
                                                >
                                                    Question type
                                                    <span style="color: red"
                                                        >*</span
                                                    >
                                                </span>
                                                <Select
                                                    v-model="questionForm.type"
                                                >
                                                    <SelectTrigger
                                                        id="framework"
                                                        class="py-1.5 pl-8 pr-2 text-sm bg-white border-black dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300"
                                                    >
                                                        <SelectValue
                                                            placeholder="Select Question Type"
                                                        />
                                                    </SelectTrigger>
                                                    <SelectContent
                                                        position="popper"
                                                        class="bg-white border-black dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300"
                                                    >
                                                        <SelectItem
                                                            value="default"
                                                        >
                                                            Default
                                                            <span class="ml-2"
                                                                >( Student
                                                                Answer in text
                                                                input, upload
                                                                file or audio
                                                                record)</span
                                                            >
                                                        </SelectItem>
                                                        <SelectItem
                                                            value="multiple"
                                                        >
                                                            Multiple Choice<span
                                                                class="ml-2"
                                                                >( Student
                                                                Select One
                                                                Options )</span
                                                            >
                                                        </SelectItem>
                                                        <SelectItem
                                                            value="checkbox"
                                                        >
                                                            CheckBox
                                                            <span class="ml-2"
                                                                >( Student
                                                                Select Multiple
                                                                Options )</span
                                                            >
                                                        </SelectItem>
                                                    </SelectContent>
                                                </Select>
                                            </div>
                                            <div
                                                class="flex flex-col space-y-1.5 options"
                                                v-if="
                                                    questionForm.type ==
                                                    'default'
                                                "
                                            >
                                                <div>
                                                    <div class="ml-2">
                                                        <input
                                                            class="!bg-black m-2"
                                                            type="radio"
                                                            id="none"
                                                            value="none"
                                                            v-model="
                                                                questionForm.fileUpload
                                                            "
                                                        />
                                                        <label for="none"
                                                            >None</label
                                                        >
                                                    </div>
                                                    <div class="ml-2">
                                                        <input
                                                            class="!bg-black m-2"
                                                            type="radio"
                                                            id="upFile"
                                                            value="upFile"
                                                            v-model="
                                                                questionForm.fileUpload
                                                            "
                                                        />
                                                        <label for="upFile"
                                                            >Allow Upload
                                                            file</label
                                                        >
                                                    </div>
                                                    <div class="ml-2">
                                                        <input
                                                            class="!bg-black m-2"
                                                            type="radio"
                                                            id="upAudio"
                                                            v-model="
                                                                questionForm.fileUpload
                                                            "
                                                            value="upAudio"
                                                        />
                                                        <label for="upAudio"
                                                            >Aloow Record
                                                            Audio</label
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="flex flex-col space-y-1.5 options"
                                                v-if="
                                                    questionForm.type ==
                                                    'multiple'
                                                "
                                            >
                                                <span
                                                    class="block font-medium text-sm text-gray-800 dark:text-white my-2"
                                                    ><input
                                                        type="radio"
                                                        class="!bg-black mr-2"
                                                        checked
                                                        disabled
                                                    />
                                                    Multiple choice
                                                </span>
                                                <div>
                                                    <span
                                                        >Options 1
                                                        <span style="color: red"
                                                            >*</span
                                                        ></span
                                                    >
                                                    <input
                                                        class="mt-1 block w-full inputStyle"
                                                        v-model="
                                                            questionForm
                                                                .options[0]
                                                        "
                                                        required
                                                    />
                                                </div>
                                                <div>
                                                    <span
                                                        >Options 2
                                                        <span style="color: red"
                                                            >*</span
                                                        ></span
                                                    >
                                                    <input
                                                        class="mt-1 block w-full inputStyle"
                                                        v-model="
                                                            questionForm
                                                                .options[1]
                                                        "
                                                        required
                                                    />
                                                </div>
                                                <div>
                                                    <span>Options 3</span>
                                                    <input
                                                        class="mt-1 block w-full inputStyle"
                                                        v-model="
                                                            questionForm
                                                                .options[2]
                                                        "
                                                    />
                                                </div>
                                                <div>
                                                    <span>Options 4</span>
                                                    <input
                                                        class="mt-1 block w-full inputStyle"
                                                        v-model="
                                                            questionForm
                                                                .options[3]
                                                        "
                                                    />
                                                </div>
                                                <div>
                                                    <span>Options 5</span>
                                                    <input
                                                        class="mt-1 block w-full inputStyle"
                                                        v-model="
                                                            questionForm
                                                                .options[4]
                                                        "
                                                    />
                                                </div>
                                                <div>
                                                    <span>Options 6</span>
                                                    <input
                                                        class="mt-1 block w-full inputStyle"
                                                        v-model="
                                                            questionForm
                                                                .options[5]
                                                        "
                                                    />
                                                </div>
                                            </div>
                                            <div
                                                class="flex flex-col space-y-1.5 options"
                                                v-if="
                                                    questionForm.type ==
                                                    'checkbox'
                                                "
                                            >
                                                <span
                                                    class="block font-medium text-sm text-gray-800 dark:text-white my-2"
                                                >
                                                    <input
                                                        type="checkbox"
                                                        class="checkbox mr-2"
                                                        checked
                                                        disabled
                                                    />
                                                    Checkbox
                                                </span>
                                                <div>
                                                    <span
                                                        >Options 1
                                                        <span style="color: red"
                                                            >*</span
                                                        ></span
                                                    >
                                                    <input
                                                        class="mt-1 block w-full inputStyle"
                                                        v-model="
                                                            questionForm
                                                                .options[0]
                                                        "
                                                        required
                                                    />
                                                </div>
                                                <div>
                                                    <span
                                                        >Options 2
                                                        <span style="color: red"
                                                            >*</span
                                                        ></span
                                                    >
                                                    <input
                                                        class="mt-1 block w-full inputStyle"
                                                        v-model="
                                                            questionForm
                                                                .options[1]
                                                        "
                                                        required
                                                    />
                                                </div>
                                                <div>
                                                    <span>Options 3</span>
                                                    <input
                                                        class="mt-1 block w-full inputStyle"
                                                        v-model="
                                                            questionForm
                                                                .options[2]
                                                        "
                                                    />
                                                </div>

                                                <div>
                                                    <span>Options 4</span>
                                                    <input
                                                        class="mt-1 block w-full inputStyle"
                                                        v-model="
                                                            questionForm
                                                                .options[3]
                                                        "
                                                    />
                                                </div>
                                                <div>
                                                    <span>Options 5</span>
                                                    <input
                                                        class="mt-1 block w-full inputStyle"
                                                        v-model="
                                                            questionForm
                                                                .options[4]
                                                        "
                                                    />
                                                </div>
                                                <div>
                                                    <span>Options 6</span>
                                                    <input
                                                        class="mt-1 block w-full inputStyle"
                                                        v-model="
                                                            questionForm
                                                                .options[5]
                                                        "
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex justify-end mt-6">
                                            <DialogFooter
                                                class="sm:justify-start"
                                            >
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
                                                        questionForm.processing ||
                                                        questionFilled
                                                    "
                                                    variant="secondary"
                                                    class="ml-6 bg-gray-800 dark:bg-gray-200 text-xs text-white dark:text-gray-800 hover:bg-gray-700 dark:hover:bg-white hover:text-white dark:hover:text-gray-800"
                                                >
                                                    ADD Question
                                                </Button>
                                            </DialogFooter>
                                        </div>
                                    </form>
                                </CardContent>
                            </DialogContent>
                        </Dialog>
                    </div>
                </div>

                <div class="mt-5" v-if="!roleUser">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead class="text-center w-[75px]">
                                    <input
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
                                <TableHead class="w-[100px] px-5">
                                    Question ID
                                </TableHead>
                                <TableHead>Question Description</TableHead>
                                <TableHead class="text-center">
                                    Type
                                </TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="question in project.questions"
                                :key="question.id"
                            >
                                <TableCell class="text-center">
                                    <input
                                        type="checkbox"
                                        class="checkbox"
                                        id="checkChile"
                                        @change="
                                            updateSelectList(
                                                question.id,
                                                $event
                                            )
                                        "
                                    />
                                </TableCell>
                                <TableCell class="text-center">
                                    {{ question.id }}
                                </TableCell>
                                <TableCell>{{
                                    question.description
                                }}</TableCell>
                                <TableCell class="text-center"
                                    >{{ question.type }}
                                    <span v-if="question.fileUpload != null">
                                        <div
                                            v-if="
                                                question.fileUpload == 'upFile'
                                            "
                                        >
                                            (file)
                                        </div>
                                        <div
                                            v-if="
                                                question.fileUpload == 'upAudio'
                                            "
                                        >
                                            (audio)
                                        </div>
                                    </span>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </div>
        </div>
        <!--if user-->

        <div id="Group" class="tabcontent" v-if="tabe == 'Group' && !roleUser">
            <div>
                <div class="flex w-full justify-between">
                    <Button
                        variant="outline"
                        class="buttonStyle ml-2"
                        @click="removeGroup"
                    >
                        Remove Group
                    </Button>
                    <div class="flex justify-end mr-4" v-if="!roleUser">
                        <Dialog>
                            <DialogTrigger as-child>
                                <Button variant="outline" class="buttonStyle">
                                    Add Group To Project
                                </Button>
                            </DialogTrigger>
                            <DialogContent
                                class="sm:max-w-md bg-white dark:bg-gray-800 text-xs dark:text-white text-gray-800"
                            >
                                <DialogHeader class="p-6 pt-0">
                                    <DialogTitle
                                        class="text-gray-800 dark:text-white text-center text-3xl"
                                        >Add Group To Project</DialogTitle
                                    >
                                </DialogHeader>
                                <CardContent>
                                    <form @submit.prevent="submit">
                                        <div
                                            class="grid items-center w-full gap-4"
                                        >
                                            <div
                                                class="flex flex-col space-y-1.5"
                                            >
                                                <span
                                                    class="block font-medium text-sm text-gray-800 dark:text-white"
                                                    >Group ID
                                                    <span style="color: red"
                                                        >*</span
                                                    ></span
                                                >
                                                <input
                                                    class="mt-1 block w-full"
                                                    v-model="form.group"
                                                    required
                                                    autofocus
                                                />
                                                <InputError
                                                    class="mt-2"
                                                    :message="form.errors.id"
                                                />
                                            </div>
                                        </div>
                                        <div class="flex justify-end mt-6">
                                            <DialogFooter
                                                class="sm:justify-start"
                                            >
                                                <DialogClose as-child>
                                                    <button
                                                        id="closeBtn"
                                                        type="button"
                                                        variant="secondary"
                                                        class="ml-6 bg-gray-800 dark:bg-gray-200 text-xs text-white dark:text-gray-800 hover:bg-gray-700 dark:hover:bg-white hover:text-white dark:hover:text-gray-800"
                                                    >
                                                        Close
                                                    </button>
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
                                                    ADD USER
                                                </Button>
                                            </DialogFooter>
                                        </div>
                                    </form>
                                </CardContent>
                            </DialogContent>
                        </Dialog>
                    </div>
                </div>

                <div class="mt-5" v-if="!roleUser">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead class="text-center w-[75px]">
                                    <input
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
                                <TableHead class="w-[100px] px-5">
                                    Group ID
                                </TableHead>
                                <TableHead>Grop name</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="group in project.groups"
                                :key="group.id"
                            >
                                <TableCell class="text-center">
                                    <input
                                        type="checkbox"
                                        class="checkbox"
                                        id="checkChile"
                                        @change="
                                            updateSelectList(group.id, $event)
                                        "
                                    />
                                </TableCell>
                                <TableCell class="text-center">
                                    {{ group.id }}
                                </TableCell>
                                <TableCell>{{ group.name }}</TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </div>
        </div>

        <div id="Admin" class="tabcontent" v-if="tabe == 'Admin' && !roleUser">
            <div>
                <div class="flex w-full justify-between">
                    <Button
                        variant="outline"
                        class="buttonStyle ml-2"
                        @click="removeAdmin"
                    >
                        Remove Admin
                    </Button>
                    <div class="flex justify-end mr-4" v-if="!roleUser">
                        <Dialog>
                            <DialogTrigger as-child>
                                <Button variant="outline" class="buttonStyle">
                                    ADD Admin
                                </Button>
                            </DialogTrigger>
                            <DialogContent
                                class="sm:max-w-md bg-white dark:bg-gray-800 text-xs dark:text-white text-gray-800"
                            >
                                <DialogHeader class="p-6 pt-0">
                                    <DialogTitle
                                        class="text-gray-800 dark:text-white text-center text-3xl"
                                        >Add Admin to Project</DialogTitle
                                    >
                                </DialogHeader>
                                <CardContent>
                                    <form
                                        @submit.prevent="addAdmin(project.id)"
                                    >
                                        <div
                                            class="grid items-center w-full gap-4"
                                        >
                                            <div
                                                class="flex flex-col space-y-1.5"
                                            >
                                                <span
                                                    class="block font-medium text-sm text-gray-800 dark:text-white"
                                                    >User Email
                                                    <span style="color: red"
                                                        >*</span
                                                    ></span
                                                >
                                                <Textarea
                                                    id="email"
                                                    class="text_scroll border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white text-black focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                                    v-model="adminForm.email"
                                                    required
                                                />
                                                <InputError
                                                    v-for="(
                                                        error, index
                                                    ) in $page.props.errors"
                                                    class="mt-2"
                                                    :message="error"
                                                />
                                            </div>
                                        </div>
                                        <div class="flex justify-end mt-6">
                                            <DialogFooter
                                                class="sm:justify-start"
                                            >
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
                                                        adminForm.processing ||
                                                        adminFilled
                                                    "
                                                    variant="secondary"
                                                    class="ml-6 bg-gray-800 dark:bg-gray-200 text-xs text-white dark:text-gray-800 hover:bg-gray-700 dark:hover:bg-white hover:text-white dark:hover:text-gray-800"
                                                >
                                                    ADD Admin
                                                </Button>
                                            </DialogFooter>
                                        </div>
                                    </form>
                                </CardContent>
                            </DialogContent>
                        </Dialog>
                    </div>
                </div>

                <div class="mt-5" v-if="!roleUser">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead class="w-[100px] text-center">
                                    <input
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
                                <TableHead class="px-5 text-center">
                                    Admin
                                </TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="admin in JSON.parse(project.admin)"
                            >
                                <TableCell class="text-center">
                                    <input
                                        type="checkbox"
                                        class="checkbox"
                                        id="checkChile"
                                        @change="
                                            updateSelectList(admin, $event)
                                        "
                                    />
                                </TableCell>
                                <TableCell class="text-center">
                                    {{ admin }}
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </div>
        </div>
    </Dashboard>
</template>
<script setup>
//Imports
import $ from "jquery";
import moment from "moment";
import Dashboard from "@/Pages/Dashboard.vue";
import InputError from "@/Components/InputError.vue";
import { Button } from "@/components/ui/button";
import { Head, useForm, usePage, router } from "@inertiajs/vue3";
import { computed, ref, onMounted, reactive } from "vue";
import { Textarea } from "@/components/ui/textarea";

import { CardContent } from "@/components/ui/card";
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

import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
//Uses

//Refs
const roleUser = ref(false);
const form = useForm({
    group: "",
});
const page = usePage();
//Props $ Emit
const props = defineProps({ project: Object });

//Computed
const dataFilled = computed(() => {
    if (form.group.length > 0) {
        return false;
    } else return true;
});

//Mathods
const submit = () => {
    router.post(
        "/project/group/" + props.project.id,
        {
            groups: [parseInt(form.group)],
        },
        {
            onSuccess: () => {
                form.reset();
                $("#closeBtn").trigger("click");
            },
        }
    );
};
const goBack = () => {
    // Go back to the previous page
    window.history.back();
};
const tabe = ref();
let noQ = true;
onMounted(() => {
    //check user role
    const role = JSON.parse(page.props.auth.user.role)[0];
    if (props.project.questions[0] != undefined) noQ = false;
    if (role == "user") roleUser.value = true;
    else roleUser.value = false;

    $("#default").trigger("click");
});

var listLen = props.project.questions.length;

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
const updateSelectList = (id, evt) => {
    //if groups is select
    if (evt.target.checked) {
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

const removeGroup = () => {
    if (
        confirm("Press OK to remove selected group from the project!") == true
    ) {
        router.delete("/project/removeGroup/" + props.project.id, {
            data: {
                groups: selectList.value,
            },
        });
        selectList.value = [];
        $('input[id="checkPR"]').prop("checked", false);
        showAll.value = true;
    }
};

//taps
const openCity = (evt, tabName) => {
    tabe.value = tabName;
    if (tabName == "Question") listLen = props.project.questions.length;
    if (tabName == "Group") listLen = props.project.groups.length;
    //uncheck all checkbox
    selectList.value = [];
    showAll.value = true;
    checkAll.value = false;
    $('input[id="checkPR"]').prop("checked", false);
    $('input[id="checkChile"]').prop("checked", false);

    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
        tablinks[i].disabled = false;
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    evt.target.className += " active";
    evt.target.disabled = true;
};

//question
const questionForm = useForm({
    description: "",
    type: "",
    options: [],
    fileUpload: "none",
    projects_id: props.project.id,
});

const addQuestion = () => {
    questionForm.post(route("question.add"), {
        onSuccess: () => {
            questionForm.reset();
            $("#closeBtn").trigger("click");
        },
        onError: (errors) => {
            console.log(errors);
        },
    });
};
const questionFilled = computed(() => {
    //check if description and type is filled
    if (questionForm.description.length > 0 && questionForm.type.length > 0) {
        //check type if not default
        if (questionForm.type != "default") {
            //check if options length is 2 or more
            if (questionForm.options.length >= 2) {
                //check if not fill
                if (
                    questionForm.options[0].length == 0 ||
                    questionForm.options[1].length == 0
                )
                    return true;
                //if fill
                else return false;
            }
            //option length smaller than 2, button disable
            else return true;
        }
        //if it default then button is not enable
        else return false;
    }
    //if not disable the button
    else return true;
});

const removeQuestion = () => {
    if (confirm("Press OK to delete selected Question!") == true) {
        router.delete("/question/delete", {
            data: {
                questions: selectList.value,
            },
        });
        //uncheck all checkbox
        selectList.value = [];
        showAll.value = true;
        checkAll.value = false;
        $('input[id="checkPR"]').prop("checked", false);
        $('input[id="checkChile"]').prop("checked", false);
    }
};

//view test
const goTest = (id) => {
    router.get("/project/test/" + id);
};

//admin
const adminForm = useForm({
    email: "",
});
const adminFilled = computed(() => {
    if (adminForm.email.length > 0) {
        return false;
    } else return true;
});
const addAdmin = (id) => {
    let users = adminForm.email.split("\n");
    users = users.filter((user) => user.trim() !== "");
    users = [...JSON.parse(props.project.admin), ...users];
    users = [...new Set(users)];

    router.post(
        "/project/admin/" + id,
        {
            users: users,
        },
        {
            onSuccess: () => {
                adminForm.reset();
                $("#closeBtn").trigger("click");
            },
            onError: (errors) => {
                errors = Object.values(errors);
            },
        }
    );
};
const removeAdmin = () => {
    if (
        confirm("Press OK to remove selected admin from the project!") == true
    ) {
        var select = selectList.value
        var admin = JSON.parse(props.project.admin)
        admin = admin.filter(item => !select.includes(item));
        console.log(admin)
        router.delete("/project/removeAdmin/" + props.project.id, {
            data: {
                admins: admin,
            },
        });
        selectList.value = [];
        $('input[id="checkPR"]').prop("checked", false);
        showAll.value = true;
    }
};
</script>
<style scoped>
@import "../../../css/button.css";
@import "../../../css/checkbox.css";
@import "../../../css/tabs.css";
@import "../../../css/textinput.css";
.options {
    overflow: auto;
    max-height: 200px;
}
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
    cursor: default !important;
}
</style>
