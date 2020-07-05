<template>
    <transition name="el-fade-in-linear">
        <div>
            <div class="background">
                <div v-for="(o, index) in goods" :key="o.id">
                    <transition name="fade" appear>
                        <div class="item round" v-bind:class="{has: curUpdate[index] > 0}">
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

                            <h3 v-if="o.inventory > 0">剩余数量：{{o.inventory}}</h3>
                            <h2 v-else>无库存剩余</h2>
                            <!--</el-card>-->
                            <p>
                                增加：
                                <el-input-number v-model="curUpdate[index]" :min="0"
                                                 label="选购数量"></el-input-number>
                            </p>

                            <h2 v-if="curUpdate[index] > 0">增加后的库存：{{o.inventory + (curUpdate[index] == null ? 0 :
                                curUpdate[index])}}</h2>
                        </div>
                    </transition>
                </div>
            </div>
            <p>
                <el-button type="primary" round>提交更新</el-button>
                <el-button type="danger" v-on:click="clearUpdate()" round>清空更新</el-button>
            </p>
        </div>
    </transition>
</template>

<script>
    export default {
        name: "StockManager",

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
                curUpdate: []
            }
        },

        methods: {
            clearUpdate() {
                this.curUpdate = []
            }
        }
    }
</script>

<style scoped>

</style>