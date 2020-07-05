<template>
    <el-container>
        <el-aside width="200px">
            <el-menu :default-active="'0'" @select="changeSelect">
                <el-menu-item index="1">
                    <span slot="title">品类管理</span>
                </el-menu-item>
                <el-menu-item index="2">
                    <span slot="title">商品管理</span>
                </el-menu-item>
                <el-menu-item index="3">
                    <span slot="title">库存管理</span>
                </el-menu-item>
                <el-menu-item>
                    <span slot="title">订单管理</span>
                </el-menu-item>
            </el-menu>
        </el-aside>
        <el-main>
            <transition name="el-fade-in-linear">
                <div v-if="status === 1">
                    <div class="background">
                        <div v-for="tag in tags" :key="tag.id">
                            <transition name="fade" appear>
                                <div class="item round-small">
                                    <el-tag class="tag">{{tag.tag}}</el-tag>
                                    <el-button type="mini" round>{{tagFunc(tag.work)}}</el-button>
                                </div>
                            </transition>
                        </div>
                    </div>
                    <div>
                        <p>
                            新建标签：
                            <el-input style="width: 200px; margin-right: 10px"
                                      placeholder="请输入内容"
                                      v-model="newTag"
                                      clearable>
                            </el-input>
                            <el-button type="primary" round>添加</el-button>
                        </p>
                    </div>
                </div>
            </transition>
            <transition name="el-fade-in-linear">
                <div v-if="status === 2">
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

                                    <h2 v-if="o.inventory > 0">剩余数量：{{o.inventory}}</h2>
                                    <h2 v-else>无库存剩余</h2>
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
                            <el-button type="primary" round>新增</el-button>
                        </p>
                    </div>
                </div>
            </transition>
        </el-main>
        <!-- 修改商品信息表单 -->
        <el-dialog title="收货地址" :visible.sync="newGoodVisible">
            <el-form :model="goods[curSelect]">
                <el-form-item label="名称">
                    <el-input v-model="goods[curSelect].name" style="width: 400px"></el-input>
                </el-form-item>
                <el-form-item label="标签">
                    <el-cascader
                            placeholder="搜索"
                            :options="tags"
                            :props="{multiple: true, value: 'id', label: 'tag'}"
                            :value="goods[curSelect].tag"
                            style="width: 400px"
                            filterable></el-cascader>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="newGoodVisible = false">取 消</el-button>
                <el-button type="primary" @click="newGoodVisible = false">确 定</el-button>
            </div>
        </el-dialog>
    </el-container>
</template>

<script>
    export default {
        name: "Admin",
        data() {
            return {
                status: 0,
                /**
                 * 标签管理
                 */
                tags: [
                    {id: 1, tag: 'abc', work: true},
                    {id: 2, tag: 'def', work: true},
                    {id: 3, tag: 'aaa', work: false}
                ],
                newTag: "",
                /**
                 * 商品管理
                 */
                goods: [
                    {id: 1, name: 'a', tag: [0, 2], price: 10, img: require("../assets/logo.png"), inventory: 100},
                    {id: 2, name: 'b', tag: [1, 2], price: 15, img: require("../assets/logo.png"), inventory: 10},
                    {id: 3, name: 'c', tag: [0, 1, 2], price: 1, img: require("../assets/logo.png"), inventory: 0},
                    {id: 4, name: 'd', tag: [2], price: 13, img: require("../assets/logo.png"), inventory: 5},
                ],
                curSelect: 0,
                newGoodVisible: false
            }
        },
        methods: {
            changeSelect(index) {
                this.status = 0
                // TODO 链接数据库
                setTimeout(() => {
                    this.status = parseInt(index)
                }, 300);
            },

            tagFunc(work) {
                if (work) {
                    return "删除"
                } else {
                    return "恢复"
                }
            },
        }
    }
</script>

<style scoped>
</style>