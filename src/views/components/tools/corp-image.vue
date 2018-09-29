<template>
    <div id="corp-image">
        <a class="btn" @click="toggleShow">更换头像</a>
        <my-upload field="img" 
            @crop-success="cropSuccess" 
            @crop-upload-success="cropUploadSuccess" 
            @crop-upload-fail="cropUploadFail" 
            v-model="show" 
            :width="300" 
            :height="300" url="/upload" 
            :params="params" 
            :headers="headers"
            img-format="png"></my-upload>
    </div>
</template>
<script>
import imgUpload from 'vue-image-crop-upload';
export default {
    data() {
        return {
            show: false,
            params: {
                token: '123456798',
                name: 'avatar'
            },
            headers: {
                smail: '*_~'
            },
            imgDataUrl: ''
        }
    },
    components: {
        'my-upload': imgUpload
    },
    methods: {
        toggleShow() {
            this.show = !this.show
        },
        cropSuccess(imgDataUrl, field){
            this.imgDataUrl = imgDataUrl
            this.$emit('imgData',imgDataUrl);
        },
        cropUploadSuccess(jsonData, field){
            this.show = false
        },
        cropUploadFail(status, field){
            this.show = false
        }
    }
}
</script>
<style lang="sass">
#corp-image
    margin-left: 1.6rem
    font-size: 1.3rem
    padding-bottom: 1rem
</style>

