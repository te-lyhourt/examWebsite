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
                            class="sm:max-w-md bg-white dark:bg-gray-800 text-xs dark:text-white text-gray-800"
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
                                                id="name"
                                                class="mt-1 block w-full"
                                                v-model="form.name"
                                                required
                                                autofocus
                                            />
                                            <InputError
                                                class="mt-2"
                                                :message="form.errors.name"
                                            />
                                        </div>
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
                                PROJECT ID
                            </TableHead>
                            <TableHead>PROJECT Name</TableHead>
                            <TableHead class="text-center">
                                Created At
                            </TableHead>
                            <TableHead class="text-center" v-if="!roleUser">
                                Project Admin
                            </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="project in projects" :key="project.id">
                            <TableCell class="text-center">
                                <input
                                    type="checkbox"
                                    class="checkbox"
                                    id="checkChile"
                                    @change="
                                        updateSelectList(project.id, $event)
                                    "
                                />
                            </TableCell>
                            <TableCell class="font-medium text-center">
                                {{ project.id }}
                            </TableCell>
                            <TableCell>{{ project.name }}</TableCell>
                            <TableCell class="text-center">
                                {{
                                    moment(project.created_at).format(
                                        "YYYY-MM-DD HH:mm"
                                    )
                                }}
                            </TableCell>
                            <TableCell class="text-center" v-if="!roleUser">
                                <div v-if="project.admin != null">
                                    {{ project.admin }}
                                </div>
                                <div v-else>
                                    <Dialog v-if="!roleUser">
                                        <DialogTrigger as-child>
                                            <Button
                                                variant="outline"
                                                class="buttonStyle"
                                            >
                                                ADD Admin
                                            </Button>
                                        </DialogTrigger>
                                        <DialogContent
                                            class="sm:max-w-md bg-white dark:bg-gray-800 text-xs dark:text-white text-gray-800"
                                        >
                                            <DialogHeader class="p-6 pt-0">
                                                <DialogTitle
                                                    class="text-gray-800 dark:text-white text-center text-3xl"
                                                    >Add Admin to
                                                    Project</DialogTitle
                                                >
                                            </DialogHeader>
                                            <CardContent>
                                                <form
                                                    @submit.prevent="
                                                        addAdmin(project.id)
                                                    "
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
                                                                <span
                                                                    style="
                                                                        color: red;
                                                                    "
                                                                    >*</span
                                                                ></span
                                                            >
                                                            <TextInput
                                                                id="email"
                                                                class="mt-1 block w-full"
                                                                v-model="
                                                                    adminForm.email
                                                                "
                                                                required
                                                                autofocus
                                                                autocomplete="email"
                                                            />
                                                            <InputError
                                                                class="mt-2"
                                                                :message="
                                                                    adminForm
                                                                        .errors
                                                                        .email
                                                                "
                                                            />
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="flex justify-end mt-6"
                                                    >
                                                        <DialogFooter
                                                            class="sm:justify-start"
                                                        >
                                                            <DialogClose
                                                                as-child
                                                            >
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
//Uses

//Refs

const roleUser = ref(false);
// const roleSAdmin= ref(false);
const page = usePage();
const form = useForm({
    name: "",
    created_by: parseInt(page.props.auth.user.id),
});
const adminForm = useForm({
    email: "",
});
//Props $ Emit
const props = defineProps({ projects: Object });

//Computed
const dataFilled = computed(() => {
    if (form.name.length > 0) {
        return false;
    } else return true;
});

const adminFilled = computed(() => {
    if (adminForm.email.length > 0) {
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

const addAdmin = (id) => {
    adminForm.post("/project/admin/" + id, {
        onSuccess: () => {
            adminForm.reset();
            $("#closeBtn").trigger("click");
        },
        onError(errors) {
            console.log(errors);
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
        selectList.value.push(id);
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
    console.log(selectList.value)
    if (confirm("Press OK to delete selected project!") == true) {
        router.delete("/project/delete", {
            data: {
                projects: selectList.value,
            },
        });
        selectList.value = []
        $('input[id="checkPR"]').prop("checked", false);
        showAll.value = true;
    }
};
const GoDetail = (id) => {
    router.get("/project/detail/" + id);
};
</script>
<style scoped>
@import "../../../css/button.css";
@import "../../../css/checkbox.css";
</style>
