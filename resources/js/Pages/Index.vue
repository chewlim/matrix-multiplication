<template>
    <Head title="Matrix Multiplication" />

    <DefaultLayout>   
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Matrix Multiplication</h2>
        </template>

        <div class="py-5">
            <div class="max-w-7xl mx-auto px-8 space-y-4">
                <h3 class="text-xl font-semibold">Size of Matrices</h3>
                <div>
                    <div class="flex items-center">
                        <div class="w-24"></div>
                        <div class="w-24 text-center">Rows</div>
                        <div class="p-3 text-lg"></div>
                        <div class="w-24 text-center">Columns</div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-24">Matrix A</div>
                        <div class="w-24"><input type="number" min="1" v-model="matrixA.row" required class="w-full"/></div>
                        <div class="p-3 text-lg">X</div>
                        <div class="w-24"><input type="number" min="1" v-model="matrixA.column" required class="w-full" /></div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-24">Matrix B</div>
                        <div class="w-24"><input type="number" min="1" v-model="matrixB.row" required class="w-full"/></div>
                        <div class="p-3 text-lg">X</div>
                        <div class="w-24"><input type="number" min="1" v-model="matrixB.column" required class="w-full" /></div>
                    </div>
                </div>

                <h3 class="text-xl font-semibold">Input</h3>
                <div>
                    <form ref="form" @submit.prevent="calculate" method="post" action="/multiply">
                        <div>
                            <h4 class="text-lg">Matrix A</h4>
                            <table class="table-fixed">
                                <tr v-for="r in matrixA.row">
                                    <td v-for="c in matrixA.column">
                                        <input type="number" :name="`matrix_a[${r-1}][${c-1}]`" required />
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="py-3">
                            <h4 class="text-lg">Matrix B</h4>
                            <table class="table-fixed">
                                <tr v-for="r in matrixB.row">
                                    <td v-for="c in matrixB.column">
                                        <input type="number" :name="`matrix_b[${r-1}][${c-1}]`" required />
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                class="rounded-full bg-white px-4 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Calculate</button>
                        </div>
                    </form>
                    
                    <InputError class="mt-2" :message="errorMessage" />

                </div>

                <h3 class="text-xl font-semibold">Result</h3>
                <div>
                    <table class="w-1/3 table-fixed border-collapse border border-slate-500 border-spacing-8 text-center">
                        <tr v-for="row in resultMatrices.items">
                            <td class="border border-slate-600" v-for="item in row">
                                {{ item }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </DefaultLayout>
</template>

<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import DefaultLayout from '@/Layouts/DefaultLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { AxiosError } from 'axios';
import { ref } from 'vue';
import { reactive } from 'vue';

defineProps<{}>();

const matrixA = reactive<{row: number, column: number}>({ row: 1, column: 1 })
const matrixB = reactive<{row: number, column: number}>({ row: 1, column: 1 })

const resultMatrices = reactive({ items: [] as String[][] })

const errorMessage = ref<string>('')

const form = ref<HTMLFormElement>()

const calculate = (event: Event) => {
    const formData = new FormData(event.target as HTMLFormElement);
    
    axios.post('/multiply', formData)
        .then(response => {
            console.log(response);
            resultMatrices.items = response.data
        }).catch((error: AxiosError | Error) => {
            if (axios.isAxiosError(error)) {
                errorMessage.value = error.response?.data.message;
            } else if (error instanceof Error) {
                errorMessage.value = error.message;
            } else {
                errorMessage.value = String(error);
            }
        });
}

</script>