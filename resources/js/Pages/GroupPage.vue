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
            <div class="mt-5">
                <Table>
                    <!-- <TableCaption>A list of your recent invoices.</TableCaption> -->
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[100px] px-5"> GROUP ID </TableHead>
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
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
    </Dashboard>
</template>
<script setup>
//Imports
import moment from "moment";
import Dashboard from "@/Pages/Dashboard.vue";
import { Head, useForm , usePage } from "@inertiajs/vue3";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table";

//Uses


//Refs
        
//Props $ Emit
defineProps({ groups: Object });

//Computed


//Mathods


//Hooks


</script>
<style scoped>
    
</style>