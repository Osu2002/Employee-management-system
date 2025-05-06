<template>
  <li class="nav-item">
    <button
      type="button"
      :class="'nav-link '+ addClass"
      role="tab"
      data-bs-toggle="tab"
      :data-bs-target="'#'+target"
      :aria-controls="target"
      aria-selected="true"
    >
      {{title}}
    </button>
  </li>
</template>

<script>
export default {
    props: {
        addClass:{
            type:String,
            default:""
        },
        target:{
            type:String,
            default:""
        },
        title:{
            type:String,
            default:""
        }
    }
};
</script>

<style>
</style>