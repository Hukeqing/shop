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
                                <h1>{{o.name}}</h1>
                                <!--suppress HtmlUnknownTarget -->
                                <img :src="o.img" alt="Image">
                                <div style="height: 20px">
                                    <template v-for="t in o.tag">
                                        <el-tag v-if="tags[t].work===true" :key="t" class="tag">
                                            {{tags[t].tag}}
                                        </el-tag>
                                    </template>
                                </div>

                                <p>价格：{{o.price}}</p>
                                <el-button type="primary" round
                                           v-on:click="curSelect = o.id - 1; newGoodVisible = true">修改信息
                                </el-button>
                                <!--</el-card>-->
                            </div>
                        </transition>
                    </div>
                </div>
                <div>
                    <p>
                        <el-button type="primary" v-on:click="newGood()" round>
                            新增
                        </el-button>
                    </p>
                </div>
            </div>
        </transition>

        <el-dialog title="商品信息" :visible.sync="newGoodVisible">
            <el-form :model="goods[curSelect]">
                <el-form-item label="名称">
                    <el-input v-model="goods[curSelect].name" style="width: 400px"></el-input>
                </el-form-item>
                <el-form-item label="标签">
                    <el-select v-model="goods[curSelect].tag" multiple clearable placeholder="请选择" style="width: 400px">
                        <el-option
                                v-for="item in tags"
                                :key="item.id"
                                :label="item.tag"
                                :value="item.id - 1">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="图片">
                    <el-upload
                            class="avatar-uploader"
                            action=""
                            :show-file-list="false"
                            :on-success="handleAvatarSuccess"
                            :before-upload="beforeAvatarUpload">
                        <!--suppress HtmlUnknownTarget -->
                        <img v-if="goods[curSelect].img" :src="goods[curSelect].img" class="avatar" alt="图标">
                        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                    </el-upload>
                </el-form-item>
                <el-form-item label="售价">
                    <el-input-number v-model="goods[curSelect].price"
                                     :precision="2"
                                     :step="0.1"></el-input-number>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="newGoodVisible = false">取 消</el-button>
                <el-button type="primary" @click="newGoodVisible = false">确 定</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script>
    export default {
        name: "GoodManager",

        data() {
            return {
                tags: [
                    {id: 1, tag: 'abc', work: true},
                    {id: 2, tag: 'def', work: true},
                    {id: 3, tag: 'aaa', work: false}
                ],
                goods: [
                    {id: 1, name: 'a', tag: [0, 2], price: 10, img: require("../../assets/logo.png"), inventory: 100},
                    {id: 2, name: 'b', tag: [1, 2], price: 15, img: require("../../assets/logo.png"), inventory: 10},
                    {id: 3, name: 'c', tag: [0, 1, 2], price: 1, img: require("../../assets/logo.png"), inventory: 0},
                    {id: 4, name: 'd', tag: [2], price: 13, img: require("../../assets/logo.png"), inventory: 5},
                ],
                curSelect: 0,
                newGoodVisible: false
            }
        },

        methods: {
            newGood() {
                this.goods.push({
                    id: this.goods[this.goods.length - 1].id + 1,
                    name: "",
                    tag: [],
                    price: 0,
                    img: "",
                    inventory: 0
                })
                this.curSelect = this.goods.length - 1
                this.newGoodVisible = true
            },

            handleAvatarSuccess(res, file) {
                this.file = file
                this.goods[this.curSelect].img = URL.createObjectURL(file.raw);
            },
            beforeAvatarUpload(file) {
                // const isJPG = file.type === 'image/jpeg';
                const isLt2M = file.size / 1024 / 1024 < 2;
                if (!isLt2M) {
                    this.$message.error('上传图片大小不能超过 2MB!');
                }
                return /*isJPG &&*/ isLt2M;
            }
        }
    }
</script>

<style scoped>
    .avatar-uploader .el-upload {
        border: 1px dashed #d9d9d9;
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