<template>
  <div>              
    <div v-for="(page, index) in pages" :key="page.id" class="input-group mb-2">
      <div class="input-group-prepend">
        <span class="input-group-text">Move</span>
      </div>
      <input v-model="page.title" name="titles[]" type="text" class="form-control" placeholder="Title...">
      <input v-model="page.page" name="pages[]" type="number" class="form-control" placeholder="Page...">
      <div class="input-group-append">
        <a href="#" @click.prevent="removePage(index)" class="btn btn-default">Remove</a>
      </div>
    </div>

    <button @click="addPage" type="button" class="btn btn-default mt-2">Add Page</button>
  </div>
</template>

<script>
export default {
  name: 'BookPages',

  props: {
    medium: {
      required: true,
    }
  },

  data () {
    return {
      total: 1,
      pages: []
    }
  },

  created () {
    this.getPages()
  },

  methods: {
    getPages () {
      axios.get(`/media/pages/${this.medium}`)
        .then(r => this.pages = r.data)
    },

    addPage () {
      this.pages.push({
        title: '',
        page: 1,
      })
    },

    removePage (index) {
      this.$delete(this.pages, index)
    }
  }
}
</script>
