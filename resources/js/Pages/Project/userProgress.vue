<template>
    <Dashboard>
        <div>
            <div>
                <Button @click="goBack" class="buttonStyle"> < Go Back</Button>
                <Head title="Group Detail" />
                <h2
                    class="mb-4 mt-4 font-medium text-5xl text-gray-700 dark:text-gray-300 grid place-content-center"
                >
                    Project: {{props.group.projectName}}
                </h2>
                <h5
                    class="mb-4 mt- font-medium text-2xl text-gray-700 dark:text-gray-300 grid place-content-center"
                >
                    Group: {{ props.group.name }}
                </h5>
            </div>
            <div class="flex justify-between">
                <div class="flex justify-end mr-4"></div>
            </div>

            <div class="mt-5">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Email</TableHead>
                            <TableHead>Progress</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="(user,index) in group.users" :key="user.id">
                            <TableCell>{{ user.email }}</TableCell>
                            <TableCell>{{ group.users[index].answersCount+  "/  " + group.totalQuestions}}</TableCell>
                            <TableCell>
                                <Button
                                    @click="goAnswer(user.id,group.project_id)" 
                                    class="ml-6 bg-gray-800 dark:bg-gray-200 text-xs text-white dark:text-gray-800 hover:bg-gray-700 dark:hover:bg-white hover:text-white dark:hover:text-gray-800">
                                    View Answer
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
import { error } from "jquery";

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
