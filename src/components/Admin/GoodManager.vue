<template>
    <div>
        <transition name="el-fade-in-linear">
            <div>
                <div class="background">
                    <div v-for="o in goods" :key="o.id">
                        <transition name="fade" appear>
                            <div class="item round">
                                <!--<el-card class="round">-->
                                <!--<p>No.{{o.id}} </p>-->
                                <h1>{{decodeURIComponent(o.name)}}</h1>
                                <!--suppress HtmlUnknownTarget -->
                                <img :src="o.img" alt="暂无图片">
                                <div style="height: 20px">
                                    <template v-for="t in o.tag">
                                        <el-tag v-if="tags[t - 1].work===true" :key="t" class="tag">
                                            {{tags[t - 1].tag}}
                                        </el-tag>
                                    </template>
                                </div>

                                <p>价格：{{o.price}}</p>
                                <el-button type="primary" round
                                           v-on:click="curSelect = o.id;
                                           newGoodVisible = true;
                                           goods[curSelect - 1].name = decodeURIComponent(goods[curSelect - 1].name);">
                                    修改信息
                                </el-button>
                                <!--</el-card>-->
                            </div>
                        </transition>
                    </div>
                </div>
                <div>
                    <p>
                        <el-button type="primary" v-on:click="newGood" round>
                            新增
                        </el-button>
                    </p>
                </div>
            </div>
        </transition>

        <el-dialog title="商品信息" :visible.sync="newGoodVisible" v-on:closed="closeDialog">
            <transition name="fade" v-if="goods.length > 0" appear>
                <el-form :model="goods[curSelect - 1]">
                    <el-form-item label="名称">
                        <el-input v-model="goods[curSelect - 1].name" style="width: 400px"></el-input>
                    </el-form-item>
                    <el-form-item label="标签">
                        <el-select v-model="goods[curSelect - 1].tag" multiple clearable placeholder="请选择"
                                   style="width: 400px">
                            <template v-for="item in tags">
                                <el-option
                                        v-if="item.work"
                                        :key="item.id"
                                        :label="item.tag"
                                        :value="item.id">
                                </el-option>
                            </template>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="图片">
                        <el-upload
                                class="avatar-uploader"
                                action="http://119.3.172.223/vue/shopAPI/goods/imgUpload.php"
                                :show-file-list="false"
                                :on-success="handleAvatarSuccess"
                                :before-upload="beforeAvatarUpload"
                                :on-error="uploadError"
                                enctype="multipart/form-data">
                            <!--suppress HtmlUnknownTarget -->
                            <img v-if="goods[curSelect - 1].img" :src="goods[curSelect - 1].img" class="avatar"
                                 alt="图标">
                            <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                        </el-upload>
                    </el-form-item>
                    <el-form-item label="售价">
                        <el-input-number v-model="goods[curSelect - 1].price"
                                         :precision="2"
                                         :step="1"></el-input-number>
                    </el-form-item>
                </el-form>
            </transition>
            <div slot="footer" class="dialog-footer">
                <el-button v-on:click="notSaveChange">取 消</el-button>
                <el-button type="primary" v-on:click="saveChange">确 定</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script>
    export default {
        name: "GoodManager",

        data() {
            return {
                tags: [],
                goods: [],
                curSelect: 1,
                newGoodVisible: false,
                isNew: false,
            }
        },

        created() {
            fetch('http://119.3.172.223/vue/shopAPI/tags.php').then(response => response.json()).then(json => {
                if (json.errorCode !== 0) {
                    this.$message.error('系统异常，请联系管理员')
                    return
                }
                this.tags = json.data
                fetch('http://119.3.172.223/vue/shopAPI/goods/goods.php').then(response => response.json()).then(json => {
                    if (json.errorCode !== 0) {
                        this.$message.error('系统异常，请联系管理员')
                        return
                    }
                    this.goods = json.data
                }).catch(() => {
                    this.$message.error('网络异常')
                })
            }).catch(() => {
                this.$message.error('网络异常')
            })
        },

        methods: {
            newGood() {
                this.goods.push({
                    id: this.goods[this.goods.length - 1].id + 1,
                    name: "",
                    tag: [],
                    price: 1,
                    img: "",
                    inventory: 0
                })
                this.curSelect = this.goods.length
                this.isNew = true;
                this.newGoodVisible = true
            },

            handleAvatarSuccess(response) {
                if (response.errorCode !== 0) {
                    this.$message.error('系统出错，请重新上传');
                } else {
                    this.goods[this.curSelect - 1].img = response.url;
                }
            },

            beforeAvatarUpload(file) {
                // const isJPG = file.type === 'image/jpeg';
                const isLt2M = file.size / 1024 / 1024 < 2;
                if (!isLt2M) {
                    this.$message.error('上传图片大小不能超过 2MB!');
                    return
                }
                return /*isJPG &&*/ isLt2M;
            },

            uploadError(err) {
                this.$message.error('上传失败，错误：' + err)
            },

            saveChange() {
                if (this.isNew) {
                    // NEW
                    fetch('http://119.3.172.223/vue/shopAPI/goods/goodNew.php?name=' + this.goods[this.curSelect - 1].name +
                        '&tag=' + this.goods[this.curSelect - 1].tag.join(';') +
                        '&img=' + this.goods[this.curSelect - 1].img +
                        '&price=' + this.goods[this.curSelect - 1].price)
                        .then(response => response.json()).then(json => {
                        if (json.errorCode !== 0) {
                            this.$message.error('系统异常，请联系管理员')
                            return
                        }
                        this.notSaveChange()
                    }).catch(() => {
                        this.$message.error('网络异常')
                    })
                } else {
                    // CHANGE
                    fetch('http://119.3.172.223/vue/shopAPI/goods/goodUpdate.php?id=' + this.curSelect +
                        '&name=' + this.goods[this.curSelect - 1].name +
                        '&tag=' + this.goods[this.curSelect - 1].tag.join(';') +
                        '&img=' + this.goods[this.curSelect - 1].img +
                        '&price=' + this.goods[this.curSelect - 1].price)
                        .then(response => response.json()).then(json => {
                        if (json.errorCode !== 0) {
                            this.$message.error('系统异常，请联系管理员')
                            return
                        }
                        this.notSaveChange()
                    }).catch(() => {
                        this.$message.error('网络异常')
                    })
                }
            },

            notSaveChange() {
                this.newGoodVisible = false
            },

            closeDialog() {
                this.$emit('flush')
            }
        }
    }
</script>

<style scoped>
    .avatar-uploader .el-upload {
        border: 10px dashed #d9d9d9;
        border-radius: 6px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .avatar-uploader .el-upload:hover {
        border-color: #409EFF;
    }

    .avatar-uploader-icon {
        font-size: 28px;
        color: #8c939d;
        width: 178px;
        height: 178px;
        line-height: 178px;
        text-align: center;
    }

    .avatar {
        width: 178px;
        height: 178px;
        display: block;
    }
</style>