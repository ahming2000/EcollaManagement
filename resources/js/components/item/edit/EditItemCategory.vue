<style lang="scss" scoped>
input[type="checkbox"] {
  display: none;

  + label {
    background-color: hsl(46, 100%, 50%);
    border: 2px solid white;
    border-radius: 30px;
    padding: 3px;
    padding-right: 10px;
    color: white;
    position: relative;
    width: 75%;
    margin-bottom: 10px;
    transition: all 0.3s ease;

    span {
      position: absolute;
      left: 0%;
      top: 50%;
      transform: translate(-50%, -50%);
      background-color: white;
      border: 2px solid hsl(46, 100%, 50%);
      border-radius: 50%;
      padding: 5px;
      width: 40px;
      height: 40px;
      vertical-align: middle;
      text-align: center;
      transition: all 0.3s ease;

      i {
        font-size: 18px;
        color: #969696;
        opacity: 0;
        transition: all 0.3s ease;
      }
    }

    p {
      text-align: end;
      margin: auto;
    }
  }

  &:checked + label {
    background-color: hsl(103, 100%, 36%);
    transition: all 0.3s ease;
    span {
      border: 2px solid hsl(46, 100%, 50%);
      transition: all 0.3s ease;
      i {
        opacity: 1;
        transition: all 0.3s ease;
      }
    }
  }
}

.no-rounded {
  border-radius: 0px;
}
</style>
<template>
  <div>
    <div class="container" style="margin-bottom: 100px">
      <div
        class="form-check d-flex justify-content-center"
        v-for="category in allItemCategories"
        v-bind:key="category.id"
      >
        <input
          class="form-check-input"
          type="checkbox"
          :value="category.id"
          :id="`category${category.id}`"
          v-model="checkedCategories"
        />

        <label class="form-check-label" :for="`category${category.id}`">
          <span>
            <i class="icofont icofont-book-mark"></i>
          </span>
          <p>{{ category.name }}</p>
        </label>
      </div>
    </div>
    <button
      class="
        btn btn-primary
        container
        d-flex
        align-items-center
        justify-content-center
        fixed-bottom
        shadow-lg
        no-rounded
        min-vw-100
      "
      @click.prevent="updateCategory()"
      style="height: 80px; z-index: 10"
    >
      <p class="text-center fw-bolder text-white m-0">保存</p>
    </button>
  </div>
</template>

<script>
export default {
  name: "edit-item-category",

  props: {
    item_id: Number,
    allCategories: Array,
    categories: Array,
  },

  data() {
    return {
      itemId: this.item_id ?? null,
      allItemCategories: this.allCategories ?? [],
      checkedCategories: this.categories.map((category) => category.id),
    };
  },

  computed: {
    classObject() {
      return {};
    },
  },

  methods: {
    updateCategory() {
      // Check and decide if need to add or remove Uncategorized
      if (this.checkedCategories.length === 0) {
        this.addUncategorized();
      } else if (
        this.checkedCategories.length > 1 &&
        this.checkedCategories.includes(1)
      ) {
        this.removeUncategorized();
      }

      const body = {
        categories: this.checkedCategories,
      };

      console.log(this.checkedCategories);
      axios
        .patch(`/item/${this.itemId}/category`, body)
        .then((res) => {
          console.log(res);
          if (res.data.ok) {
            if (res.data.message !== "") {
              this.$emit("onResponse", res.data.messages, "success");
            } else {
              this.$emit("onResponse", res.data.messages, "error");
            }
          }
        })
        .catch((error) => {
          console.error(error);
          this.$emit("onResponse", error.message, "error");
        });
    },

    removeUncategorized() {
      console.log("removeUncategorized()");
      this.checkedCategories = this.checkedCategories.filter(
        (categoryId) => categoryId != 1
      );
    },

    addUncategorized() {
      console.log("addUncategorized()");
      this.checkedCategories = [1, ...this.checkedCategories];
    },
  },
};
</script>
