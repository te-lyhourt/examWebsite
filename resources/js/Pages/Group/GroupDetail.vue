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
            <div class="flex justify-between">
                <div class="flex justify-start mr-4">
                    <Dialog>
                        <DialogTrigger as-child>
                            <Button variant="outline" class="buttonStyle">
                                Create New User
                            </Button>
                        </DialogTrigger>
                        <DialogContent
                            class="sm:max-w-md bg-white dark:bg-gray-800 text-xs dark:text-white text-gray-800"
                        >
                            <DialogHeader class="p-6 pt-0">
                                <DialogTitle
                                    class="text-gray-800 dark:text-white text-center text-3xl"
                                    >Create New User</DialogTitle
                                >
                            </DialogHeader>
                            <CardContent>
                                <form @submit.prevent="addUser">
                                    <div class="grid items-center w-full gap-4">
                                        <div class="flex flex-col space-y-1.5">
                                            <span
                                                class="block font-medium text-sm text-gray-800 dark:text-white"
                                                >Email
                                                <span style="color: red"
                                                    >*</span
                                                ></span
                                            >
                                            <TextInput
                                                id="email"
                                                class="mt-1 block w-full"
                                                v-model="userForm.email"
                                                required
                                                autofocus
                                                autocomplete="username"
                                            />
                                            <InputError
                                                class="mt-2"
                                                :message="userForm.errors.email"
                                            />
                                        </div>
                                        <div class="flex flex-col space-y-1.5">
                                            <span
                                                class="block font-medium text-sm text-gray-800 dark:text-white"
                                                >Password
                                                <span style="color: red"
                                                    >*</span
                                                >
                                            </span>
                                            <TextInput
                                                id="password"
                                                type="password"
                                                class="mt-1 block w-full"
                                                v-model="userForm.password"
                                                required
                                                autocomplete="current-password"
                                            />
                                            <InputError
                                                class="mt-2"
                                                :message="pw8"
                                            />
                                            <InputError
                                                class="mt-2"
                                                :message="userForm.errors.password"
                                            />
                                        </div>
                                        <div class="flex flex-col space-y-1.5">
                                            <Label
                                                for="framework"
                                                class="text-gray-800 dark:text-white"
                                                >Role
                                                <span style="color: red"
                                                    >*</span
                                                >
                                            </Label>
                                            <Select v-model="userForm.role">
                                                <SelectTrigger
                                                    id="framework"
                                                    class="py-1.5 pl-8 pr-2 text-sm bg-white border-black dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300"
                                                >
                                                    <SelectValue
                                                        placeholder="Select User Role"
                                                    />
                                                </SelectTrigger>
                                                <SelectContent
                                                    position="popper"
                                                    class="bg-white border-black dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300"
                                                >
                                                    <SelectItem
                                                        value="project admin"
                                                    >
                                                        PROJECT ADMIN
                                                    </SelectItem>
                                                    <SelectItem value="user">
                                                        USER
                                                    </SelectItem>
                                                </SelectContent>
                                            </Select>
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
                                                    userDataFilled
                                                "
                                                variant="secondary"
                                                class="ml-6 bg-gray-800 dark:bg-gray-200 text-xs text-white dark:text-gray-800 hover:bg-gray-700 dark:hover:bg-white hover:text-white dark:hover:text-gray-800"
                                            >
                                                CREATE USER
                                            </Button>
                                        </DialogFooter>
                                    </div>
                                </form>
                            </CardContent>
                        </DialogContent>
                    </Dialog>
                </div>
                <div class="flex justify-end mr-4">
                <Dialog>
                    <DialogTrigger as-child>
                        <Button variant="outline" class="buttonStyle">
                            Add User to Group
                        </Button>
                    </DialogTrigger>
                    <DialogContent
                        class="sm:max-w-lg bg-white dark:bg-gray-800 text-xs dark:text-white text-gray-800"
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
                                            >User Email
                                            <span style="color: red"
                                                >*</span
                                            ></span
                                        >
                                        <!-- <TextInput
                                            

                                        /> -->
                                        <Textarea
                                            placeholder="Add user emails here."
                                            class="text_scroll border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white text-black focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                            v-model="form.users"
                                            required
                                            autofocus
                                        />
                                        <InputError
                                            v-for="(error, index) in $page.props.errors"
                                            class="mt-2"
                                            :message="error"
                                        />
                                    </div>
                                </div>
                                <div class="flex justify-end mt-6 w-full">
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


            </div>


            <div class="mt-5">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[100px] px-5">
                                USER ID
                            </TableHead>
                            <TableHead>Email</TableHead>
                            <TableHead class="text-center">Added By</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="user in group.users" :key="user.id">
                            <TableCell class="text-center">
                                {{ user.id }}
                            </TableCell>
                            <TableCell>{{ user.email }}</TableCell>
                            <TableCell class="text-center">{{
                                user.group_user.added_by
                            }}</TableCell>
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
import { computed, reactive, ref } from "vue";

import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
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
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";

//Refs
const form = useForm({
    users: "",
});
const page = usePage();

const userForm = useForm({
    email: "",
    password: "",
    role: "",
    created_by: parseInt(page.props.auth.user.id),
});
//Props $ Emit
const props = defineProps({ group: Object });

//Computed
const dataFilled = computed(() => {
    if (form.users.length > 0) {
        return false;
    } else return true;
});


const userDataFilled = computed(() => {
    if (
        userForm.email.length > 0 &&
        userForm.password.length >= 8 &&
        userForm.role.length > 0
    ) {
        return false;
    } else return true;
});

const pw8 = computed(() => {
    if (userForm.password.length > 0 && userForm.password.length < 8) {
        return "password at least 8 digit";
    }
});

//Mathods

const submit = () => {
    let users = form.users.split("\n");
    users = users.filter(user => user.trim() !== '');
    router.post(
        "/group/user/" + props.group.id,
        {
            users: users,
        },
        {
            onSuccess: () => {
                form.reset();
                $("#closeBtn").trigger("click");
            },
            onError: (errors) => {
                errors = Object.values(errors);
            },
        }
    );
};
const addUser = ()=>{
    userForm.post('/group/createUser/'+props.group.id,{
        onSuccess: () => {
            userForm.reset();
            $("#closeBtn").trigger("click");
        },
    })
}
const goBack = () => {
    // Go back to the previous page
    window.history.back();
};

</script>
<style scoped>
@import "../../../css/button.css";
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
