<template>
    <Head title="Matrix Multiplication" />

    <DefaultLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Matrix Multiplication</h2>
        </template>

        <div class="py-5">
            <div class="max-w-7xl mx-auto px-8 space-y-4">
                <h3 class="text-xl font-semibold">Step 1: Set size of each matrices</h3>
                <div>
                    <div class="flex items-center">
                        <div class="w-24"></div>
                        <div class="w-24 text-center">Rows</div>
                        <div class="p-3 text-lg"></div>
                        <div class="w-24 text-center">Columns</div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-24">Matrix A</div>
                        <div class="w-24">
                            <TextInput
                                type="number" 
                                v-model="matrixA.row"
                                min="1" 
                                required 
                                @update:modelValue="(v) => matrixA.row = +v"
                                class="w-24"/>
                        </div>
                        <div class="p-3 text-lg">X</div>
                        <div class="w-24">
                            <TextInput
                                type="number" 
                                v-model="matrixA.column"
                                min="1" 
                                required 
                                @update:modelValue="(v) => matrixA.column = +v"
                                class="w-24"
                                :class="{
                                    'border border-rose-500': hasError && matrixB.row != matrixA.column
                                }"
                                />
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-24">Matrix B</div>
                        <div>
                            <TextInput
                                type="number" 
                                v-model="matrixB.row"
                                min="1" 
                                required 
                                @update:modelValue="(v) => matrixB.row = +v"
                                class="w-24"
                                :class="{
                                    'border border-rose-500': hasError && matrixB.row != matrixA.column
                                }"
                                />
                        </div>
                        <div class="p-3 text-lg">X</div>
                        <div>
                            <TextInput
                                type="number" 
                                v-model="matrixB.column"
                                min="1" 
                                required 
                                @update:modelValue="(v) => matrixB.column = +v"
                                class="w-24"/>
                        </div>
                    </div>
                </div>

                <h3 class="text-xl font-semibold">Step 2: Enter values for each matrix</h3>
                <p></p>
                <div>
                    <form ref="form" @submit.prevent="calculate" method="post" action="/multiply">
                        <div class="flex items-center space-x-4 border p-5 overflow-scroll">
                            <h4 class="text-lg">Matrix A</h4>
                            <table class="table-fixed ">
                                <tr v-for="r in matrixA.row">
                                    <td v-for="c in matrixA.column">
                                        <TextInput type="number" 
                                            :name="`matrix_a[${r - 1}][${c - 1}]`" 
                                            model-value="1"
                                            min="1" 
                                            required 
                                            class="w-24"/>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="flex items-center space-x-4 border p-5 overflow-scroll">
                            <h4 class="text-lg">Matrix B</h4>
                            <table class="table-fixed">
                                <tr v-for="r in matrixB.row">
                                    <td v-for="c in matrixB.column">
                                        <TextInput type="number" 
                                            :name="`matrix_b[${r - 1}][${c - 1}]`" 
                                            model-value="1"
                                            min="1" 
                                            required 
                                            class="w-24"/>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="py-4">
                            <PrimaryButton type="submit">Calculate</PrimaryButton>
                        </div>
                    </form>

                    <InputError class="mt-2" :message="errorMessage" />

                </div>

                <div v-if="resultMatrices.items && resultMatrices.items.length > 0">
                    <h3 class="text-xl font-semibold pb-2">Result for multiplication of Matrix A & B</h3>
                    <div>
                        <table class="w-1/3 table-fixed border-collapse border border-slate-100 border-spacing-8 text-center">
                            <tr v-for="row in resultMatrices.items">
                                <td class="border border-slate-300" v-for="item in row">
                                    {{ item }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </DefaultLayout>
</template>

<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import DefaultLayout from '@/Layouts/DefaultLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { AxiosError } from 'axios';
import { computed } from 'vue';
import { watch } from 'vue';
import { ref } from 'vue';
import { reactive } from 'vue';

defineProps<{}>();

interface MatrixResult {
  items: string[][]
}

const matrixA = reactive<{ row: number, column: number }>({ row: 1, column: 1 })
const matrixB = reactive<{ row: number, column: number }>({ row: 1, column: 1 })

const resultMatrices = reactive<MatrixResult>({ items: [] as string[][] })

const errorMessage = ref<string>('')

const form = ref<HTMLFormElement>()

watch(matrixA, () => {
    resetResult();
})

watch(matrixB, () => {
    resetResult();
})

const resetResult = () => {
    resultMatrices.items = []
    errorMessage.value = ''
}

const hasError = computed(() => {
    return errorMessage.value != ''
})


const calculate = (event: Event) => {
    errorMessage.value = '';

    const formData = new FormData(event.target as HTMLFormElement);

    axios.post<string[][]>('/multiply', formData)
        .then((response) => {
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