<template>
    <Dashboard>
        <div>
            <div>
                <Button @click="goBack" class="buttonStyle"> < Go Back</Button>
                <Head title="Group Detail" />
                <h2
                    class="mb-4 mt-4 font-medium text-5xl text-gray-700 dark:text-gray-300 grid place-content-center"
                >
                    {{ props.group.name }}
                </h2>
            </div>
            <div class="flex justify-end mr-4">
                <Dialog>
                    <DialogTrigger as-child>
                        <Button variant="outline" class="buttonStyle">
                            Add User to Group
                        </Button>
                    </DialogTrigger>
                    <DialogContent
                        class="sm:max-w-md bg-white dark:bg-gray-800 text-xs dark:text-white text-gray-800"
                    >
                        <DialogHeader class="p-6 pt-0">
                            <DialogTitle
                                class="text-gray-800 dark:text-white text-center text-3xl"
                                >Add User to Group</DialogTitle
                            >
                        </DialogHeader>
                        <CardContent>
                            <form @submit.prevent="submit">
                                <div class="grid items-center w-full gap-4">
                                    <div class="flex flex-col space-y-1.5">
                                        <span
                                            class="block font-medium text-sm text-gray-800 dark:text-white"
                                            >User ID
                                            <span style="color: red"
                                                >*</span
                                            ></span
                                        >
                                        <TextInput
                                            class="mt-1 block w-full"
                                            v-model="form.users"
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
                                            ADD USER
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
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[100px] px-5">
                                USER ID
                            </TableHead>
                            <TableHead>Email</TableHead>
                            <TableHead>Added By</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="user in group.users" :key="user.id">
                            <TableCell class="text-center">
                                {{ user.id }}
                            </TableCell>
                            <TableCell>{{ user.email }}</TableCell>
                            <TableCell class="text-center">{{ user.group_user.added_by }}</TableCell>
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
//Uses

//Refs
const form = useForm({
    users: "",
});

//Props $ Emit
const props = defineProps({ group: Object });

//Computed
const dataFilled = computed(() => {
    if (form.users.length > 0) {
        return false;
    } else return true;
});

//Mathods
const submit = () => {
    router.post(
        "/group/user/" + props.group.id,
        {
            users: [parseInt(form.users)],
        },
        {
            onSuccess: (response) => {
                console.log(response);
            },
            onFinish: () => {
                form.reset()
                $("#closeBtn").trigger("click");
            },
        }
    );
};
const goBack = () => {
    // Go back to the previous page
    window.history.back();
};
//Hooks
</script>
<style scoped>
@import "../../../css/button.css";
</style>
