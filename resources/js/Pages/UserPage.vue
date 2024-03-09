<template>
    <Dashboard>
        <div>
            <div>
                <Head title="User" />
                <h2
                    class="mb-4 mt-4 font-medium text-5xl text-gray-700 dark:text-gray-300 grid place-content-center"
                >
                    User List
                </h2>
            </div>
            <div class="flex w-full justify-between">
                <Button
                    variant="outline"
                    class="buttonStyle ml-2"
                    @click="deleteUser"
                >
                    DELETE USER
                </Button>

                <div class="flex justify-end mr-4">
                    <Dialog>
                        <DialogTrigger as-child>
                            <Button variant="outline" class="buttonStyle">
                                ADD USER
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
                                <form @submit.prevent="submit">
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
                                                v-model="form.email"
                                                required
                                                autofocus
                                                autocomplete="username"
                                            />
                                            <InputError
                                                class="mt-2"
                                                :message="form.errors.email"
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
                                                v-model="form.password"
                                                required
                                                autocomplete="current-password"
                                            />
                                            <InputError
                                                class="mt-2"
                                                :message="pw8"
                                            />
                                            <InputError
                                                class="mt-2"
                                                :message="form.errors.password"
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
                                            <Select v-model="form.role">
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
                                                        value="system admin"
                                                    >
                                                        SYSTEM ADMIN
                                                    </SelectItem>
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

            <div class="mt-5">
                <Table>
                    <!-- <TableCaption>A list of your recent invoices.</TableCaption> -->
                    <TableHeader>
                        <TableRow>
                            <TableHead class="text-center">
                                <input
                                    type="checkbox"
                                    class="checkbox"
                                    v-model="checkAll"
                                    @click="selectAll"
                                />
                            </TableHead>
                            <TableHead class="w-[100px] px-5">
                                USER ID
                            </TableHead>
                            <TableHead>USER Email</TableHead>
                            <TableHead class="text-center">USER Role</TableHead>
                            <TableHead class="text-center">
                                Created At
                            </TableHead>
                            <TableHead class="text-center">
                                Created By
                            </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="user in users" :key="user.id">
                            <TableCell class="text-center">
                                <input
                                    type="checkbox"
                                    class="checkbox"
                                    id="checkChile"
                                    @change="
                                        updateDeleteUsersList(user.id, $event)
                                    "
                                />
                            </TableCell>
                            <TableCell class="text-center font-medium">
                                {{ user.id }}
                            </TableCell>
                            <TableCell>{{ user.email }}</TableCell>
                            <TableCell class="text-center">
                                <div
                                    v-for="(role, index) in JSON.parse(
                                        user.role
                                    )"
                                    :key="index"
                                >
                                    {{ role }}
                                </div>
                            </TableCell>
                            <TableCell class="text-center">
                                {{
                                    moment(user.created_at).format(
                                        "YYYY-MM-DD HH:mm"
                                    )
                                }}
                            </TableCell>
                            <TableCell class="text-center">
                                {{ user.created_by }}
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
import Dashboard from "@/Pages/Dashboard.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";
import moment from "moment";
import { computed, reactive, ref } from "vue";

//USE
import { Label } from "@/components/ui/label";
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

//Props $ Emit
defineProps({ users: Object });

//Data
const page = usePage();
const checkAll = ref(false);
let deleteUsersList = [];

const form = useForm({
    email: "",
    password: "",
    role: "",
    created_by: parseInt(page.props.auth.user.id),
});

//Computed
const dataFilled = computed(() => {
    if (
        form.email.length > 0 &&
        form.password.length >= 8 &&
        form.role.length > 0
    ) {
        return false;
    } else return true;
});

const pw8 = computed(() => {
    if (form.password.length > 0 && form.password.length < 8) {
        return "password at least 8 digit";
    }
});
//Mathods

//check and uncheck all checkbox
const selectAll = () => {
    if (checkAll.value) {
        $('input[id="checkChile"]').trigger("click");
    } else {
        $('input[id="checkChile"]').trigger("click");
    }
};

//add user to database
const submit = () => {
    form.post(route("user.add"), {
        onSuccess: () => {
            form.reset();
            $("#closeBtn").trigger("click");
        },
    });
};
const updateDeleteUsersList = (id, event) => {
    //if user is select
    if (event.target.checked) {
        deleteUsersList.push(id);
    } else {
        const index = deleteUsersList.indexOf(id);
        if (index !== -1) {
            deleteUsersList.splice(index, 1);
        }
    }
};

const deleteUser = () => {
    if (confirm("Press OK to delete selected user!") == true) {
        console.log("You pressed OK!");
    } else {
        console.log("You canceled!");
    }
};
</script>
<style scoped>
@import "../../css/button.css";

.checkbox {
    background-color: black;
    border-radius: 0.35rem /* 6px */;
    border: white 2px;
}
.checkbox:checked {
    background-color: black;
}

.checkbox:focus {
    background-color: black;
    --tw-ring-shadow: 0;
}
</style>
