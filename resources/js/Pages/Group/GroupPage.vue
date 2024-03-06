<template>
    <Dashboard>
        <div>
            <div>
                <Head title="Group" />
                <h2
                    class="mb-4 mt-4 font-medium text-5xl text-gray-700 dark:text-gray-300 grid place-content-center"
                >
                    Group List
                </h2>
            </div>
            <div class="flex justify-end mr-4">
                <Dialog>
                    <DialogTrigger as-child>
                        <Button variant="outline" class="buttonStyle">
                            Create New Group
                        </Button>
                    </DialogTrigger>
                    <DialogContent
                        class="sm:max-w-md bg-white dark:bg-gray-800 text-xs dark:text-white text-gray-800"
                    >
                        <DialogHeader class="p-6 pt-0">
                            <DialogTitle
                                class="text-gray-800 dark:text-white text-center text-3xl"
                                >Create New Group</DialogTitle
                            >
                        </DialogHeader>
                        <CardContent>
                            <form @submit.prevent="submit">
                                <div class="grid items-center w-full gap-4">
                                    <div class="flex flex-col space-y-1.5">
                                        <span
                                            class="block font-medium text-sm text-gray-800 dark:text-white"
                                            >Group Name
                                            <span style="color: red"
                                                >*</span
                                            ></span
                                        >
                                        <TextInput
                                            id="email"
                                            class="mt-1 block w-full"
                                            v-model="form.name"
                                            required
                                            autofocus
                                            autocomplete="username"
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
                                                form.processing || dataFilled
                                            "
                                            variant="secondary"
                                            class="ml-6 bg-gray-800 dark:bg-gray-200 text-xs text-white dark:text-gray-800 hover:bg-gray-700 dark:hover:bg-white hover:text-white dark:hover:text-gray-800"
                                        >
                                            ADD GROUP
                                        </Button>
                                    </DialogFooter>
                                </div>
                            </form>
                        </CardContent>
                    </DialogContent>
                </Dialog>
            </div>

            <div class="mt-5">
                <Table>
                    <!-- <TableCaption>A list of your recent invoices.</TableCaption> -->
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[100px] px-5">
                                GROUP ID
                            </TableHead>
                            <TableHead>GROUP Name</TableHead>
                            <TableHead class="text-center">
                                Created At
                            </TableHead>
                            <TableHead class="text-center">
                                Created By
                            </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="group in groups" :key="group.id">
                            <TableCell class="text-center">
                                {{ group.id }}
                            </TableCell>
                            <TableCell>{{ group.name }}</TableCell>
                            <TableCell class="text-center">
                                {{
                                    moment(group.created_at).format(
                                        "YYYY-MM-DD HH:mm"
                                    )
                                }}
                            </TableCell>
                            <TableCell class="text-center">
                                {{ group.created_by }}
                            </TableCell>
                            <TableCell class="text-center">
                                <Button
                                    variant="outline"
                                    class="buttonStyle"
                                    @click="GoDetail(group.id)"
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
import Dashboard from "@/Pages/Dashboard.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, useForm, usePage, router } from "@inertiajs/vue3";
import { computed, ref } from "vue";

import { Button } from "@/components/ui/button";
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
import GroupDetail from "./GroupDetail.vue";
import { error } from "jquery";
//Uses

//Refs
const page = usePage();
const form = useForm({
    name: "",
    created_by: parseInt(page.props.auth.user.id),
});

//Props $ Emit
defineProps({ groups: Object });

//Computed
const dataFilled = computed(() => {
    if (form.name.length > 0) {
        return false;
    } else return true;
});

//Mathods
const submit = () => {
    form.post(route("group.add"), {
        onSuccess: () => {
            form.reset()
            $("#closeBtn").trigger("click");
        },
    });
};
const GoDetail = (id) => {
    router.get('/group/detail/'+id);
};
//Hooks
</script>
<style scoped>
@import "../../../css/button.css";
</style>
