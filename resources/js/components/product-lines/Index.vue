<template>
    <div>
        <v-container>
            <v-row v-if="$route.params.id == null">
                <v-col cols="12" sm="6" md="4" lg="3" v-for="productLine in productLines" :key="productLine.id">
                    <v-card :to="`/product-lines/${productLine.id}`">
                        <v-card-title>{{productLine.name}}</v-card-title>
                        <v-card-text>{{productLine.description}}</v-card-text>
                    </v-card>
                </v-col>
            </v-row>
            <router-view v-else />
        </v-container>
    </div>
</template>

<script setup>
import { ref } from 'vue'

const productLines = ref([])

const loadData = async () => {
    axios.get('/api/product-lines').then((response) => {
        productLines.value = response.data.data;
    }).catch((error) => {
        console.error(error);
    });
}

const editItem = (item) => {

}

const deleteItem = (item) => {

}

loadData()
</script>

<style>
/* Add any custom styles if needed */
</style>
