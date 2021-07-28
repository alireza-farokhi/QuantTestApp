<template>
  <v-app>
    <v-row>
      <v-col class="d-flex ml-auto" cols="12" sm="12" xsm="12">
        <v-select
          :items="currencies"
          item-text="name"
          item-value="id"
          v-model="currency"
          label="currency"
          data-vv-name="currency"
          required
          @change="Selected"
        ></v-select>
        <v-btn x-large inline color="success" @click="search">search</v-btn>
      </v-col>
    </v-row>
    <v-row>
      <v-col class="d-flex ml-auto" cols="12" sm="12" xsm="12">
        <v-list>
          <v-list-item>
            <v-list-item-content>
              rate:
              <v-list-item-title v-text="selected"></v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list>
      </v-col>
      <v-col class="d-flex ml-auto" cols="12" sm="12" xsm="12">
        <v-list>
          prices:
          <v-list-item v-for="(price, i) in markets" :key="i">
            <v-list-item-content>
              Price:
              <v-list-item-title v-text="price"></v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list>
      </v-col>
    </v-row>
  </v-app>
</template>


<script>
import axios from "axios";
export default {
  data() {
    return {
      currency: "",
      selected: null,
      currencies: [],
      markets: [],
    };
  },
  mounted() {
    this.getCurrencies();
  },
  methods: {
    Selected(i) {
      this.selected = i;
      this.markets = [];
    },
    getCurrencies() {
      axios
        .get("/api/currencies", {
          headers: {
            Authorization:
              `Bearer ` + JSON.parse(localStorage.getItem("access_token")),
          },
        })

        .then((response) => {
          console.log(response.data);
          this.currencies = response.data;
        })
        .catch((error) => {
          console.log("ERRRR:: ", error);
        });
    },

    search() {
      axios
        .get("/api/markets/?exchange=" + this.selected, {
          headers: {
            Authorization:
              `Bearer ` + JSON.parse(localStorage.getItem("access_token")),
          },
        })

        .then((response) => {
          this.markets = response.data[0].prices;
        })
        .catch((error) => {
          console.log("ERRRR:: ", error);
        });
    },
  },
};
</script>
