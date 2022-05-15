<template>
<div class="content">

    <div class="modal" tabindex="-1" ref="updateModal">
        <div class="modal-dialog card">
            <div class="modal-content card-header">
                <h3>編集</h3>
                <form v-on:submit.prevent="updateMyProfile" class="card-body">
                    <div class="form-group row">
                        <label class="alert alert-danger p-2"
                            v-text="message"
                            v-if="message"></label>
                    </div>
                    <div class="form-group row" >
                        <div class="container py-3">
                            <div class="input-group custom-file-button">
                                <label v-if="!user.upload_icon" for="account-icon" class="input-group-text w-100 justify-content-center p-0">
                                    <div class="d-flex h-50 align-content-center">
                                        <p class="text-center">画像を選択
                                        </p>
                                        <i class="bi bi-file-person-fill fs-5"></i>
                                    </div>
                                </label>
                                <input type="file"
                                    class="form-control"
                                    id="account-icon"
                                    ref='file'
                                    @change="upload"
                                >
                            </div>
                            <div class="text-center mt-3">
                                <img :src="picture" class="img-fluid img-thumbnail rounded-circle text-center" style="width:100px; height:100px; object-fit:cover;">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="update-display-name" class="col-sm-3 col-from-lable w-100">表示アカウント名</label>
                        <input type="text"
                            class="col-sm-9 form-control"
                            id="update-display-name"
                            v-bind:value="user.display_name"
                            v-on:input="inputDisplayName"
                            >
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-5">完了</button>
                </form>
            </div>
        </div>
    </div>
        <div class="modal" tabindex="-10" ref="waitModal" data-bs-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <p>変更中です</p>
                    </div>
                </div>
            </div>
        </div>
</div>
</template>

<script>
import { Modal } from 'bootstrap';

export default ({
    data() {
        return {
            user: this.$parent.user,
            updateModalObj: null,
            waitModalObj: null,
            message: null,
            error_message: null,
            updateParam: {},
            picture: null,
            fileResult: null,
        }
    },
    mounted() {
        this.updateModalObj = new Modal(this.$refs.updateModal,{keyboard: true});
        this.waitModalObj = new Modal(this.$refs.waitModal,{keyboard: true});
    },
    created(){
        this.updateParam.display_name = this.user.display_name;
        this.picture = '/storage/' + this.$parent.user.icon;
    },
    methods:{
        showUpdateModal(){
            this.message = null;
            this.updateModalObj.show();
        },
        inputDisplayName(){
            this.updateParam.display_name = event.target.value;
        },
        updateMyProfile(){
            this.waitModalObj.show();

            axios.post('/api/user/update', this.updateParam)
                .then((res) => {
                    if(!this.fileResult){
                        throw new Error();
                    }
                    this.waitModalObj.hide();
                    this.updateModalObj.hide();
                    this.$parent.user = res.data.user;
                    console.log(res.data.user.icon);
                    this.$store.commit('updateUser', res.data.user);
                    this.updateMyProfile.hide();
                })
                .catch((error) => {
                    this.waitModalObj.hide();
                    var error_message = '編集に失敗しました。';
                    this.message = error_message;
                })
        },
        async upload(event) {
            const files = event.target.files || event.dataTransfer.files;
            const file = files[0];
            var self = this;

            if(this.checkFile(file)){
                    this.getBase64(file).then(image => {

                    const originalImg = new Image();
                    originalImg.src = image;
                    originalImg.onload = () => {
                        const resizedCanvas = this.createResizedCanvasElement(originalImg);
                        const resizedBase64 = resizedCanvas.toDataURL(file);
                        this.picture = resizedBase64;
                        this.updateParam['icon_base64'] = resizedBase64;
                        return resizedBase64;
                    }
                })
                .catch(error => this.setError(error, '画像のアップロードに失敗しました。'));
            }
        },
        getBase64(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = () => resolve(reader.result);
                reader.onerror = error => reject(error);
            });
        },
        checkFile(file) {
            this.fileResult =  true;
            const SIZE_LIMIT = 500000000;
            if(!file) {
                this.fileResult = false;
            }
            if(file.type !== 'image/jpeg' && file.type !== 'image/png') {
                this.fileResult = false;
            }
            if(file.size > SIZE_LIMIT){
                this.fileResult = false;
            }
            return this.fileResult;
        },
        createResizedCanvasElement (originalImg) {
            const originalImgWidth = originalImg.width
            const orifinalImgHeight = originalImg.height

            const [resizedWidth, resizedHeight] = this.resizeWidthAndHeight(originalImgWidth, orifinalImgHeight)
            const canvas = document.createElement('canvas')
            const ctx = canvas.getContext('2d')
            canvas.width = resizedWidth
            canvas.height = resizedHeight

            ctx.drawImage(originalImg, 0, 0, resizedWidth, resizedHeight)
            return canvas
        },

        resizeWidthAndHeight (width, height) {
            const MAX_WIDTH = 400
            const MAX_HEIGHT = 400

            if (width > height) {
                if (width > MAX_WIDTH) {
                height *= MAX_WIDTH / width
                width = MAX_WIDTH
                }
            } else {
                if (height > MAX_HEIGHT) {
                width *= MAX_HEIGHT / height
                height = MAX_HEIGHT
                }
            }
            return [width, height]
        },
    }
})
</script>
