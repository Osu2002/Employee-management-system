<template>
  <div :class="'tab-pane fade '+addClass" :id="id" role="tabpanel">
    <div v-if="title != '' || subtitle != ''" class="card-header pb-0">
      <h5 v-if="title != ''">{{title}}</h5>
      <p v-if="subtitle != ''">{{subtitle}}</p>
    </div>
    <hr v-if="title != '' || subtitle != ''" />
    <slot/>
  </div>
</template>

<script>
export default {
    props:{
        addClass:{
            type:String,
            default:""
        },
        id:{
            type:String,
            default:""
        
        },
        title:{
            type:String,
            default:""
        
        },
        subtitle:{
            type:String,
            default:""
        }
    }
};
</script>

<style>
</style>