<template>
    <div>
        <container>
            <v-row>
                <v-col cols="12" sm="6" md="4" lg="3" v-for="productLine in productLines" :key="productLine.id">
                    <v-card>
                        <v-card-title>{{productLine.name}}</v-card-title>
                        <v-card-text>{{productLine.description}}</v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </container>
    </div>
  </template>

  <script>
  import axios from 'axios';

  export default {
    data() {
      return {
        search: '',
        page: 1,
        productLines: [],
        totalPages: 1,
        headers: [
          { text: 'Name', value: 'name' },
          { text: 'Description', value: 'description' },
          { text: 'Actions', value: 'actions', sortable: false },
        ],
      };
    },
    methods: {
      loadData() {
        axios
          .get('/api/product-lines', {
            params: {
              page: this.page,
            },
          })
          .then((response) => {
            console.log(response.data);
                this.productLines = response.data.data;
                // this.totalPages = response.last_page; // Assuming `last_page` gives total pages
          })
          .catch((error) => {
            console.error(error);
          });
      },
      editItem(item) {
        console.log('Edit:', item);
        // Implement edit functionality here
      },
      deleteItem(item) {
        console.log('Delete:', item);
        // Implement delete functionality here
      },
    },
    created() {
      this.loadData();
    },
  };
  </script>

  <style>
  /* Add any custom styles if needed */
  </style>
