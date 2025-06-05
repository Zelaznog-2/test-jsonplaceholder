<script setup >
import { Link } from '@inertiajs/vue3';
import { defineProps, defineEmits } from 'vue';

const emit = defineEmits();

// props
defineProps({
    form: Object,
    users: Array
});

const submit = () => {
    emit('submit');
}

</script>
<template>
    <div>
        <div class="p-20 space-y-6">
            <form action="#">
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-3">
                        <label for="first-name" class="block mb-2 text-sm font-medium  dark:text-black">Usuario</label>
                        <select type="text" v-model="form.user_id"
                            class="shadow-sm bg-gray-50 border border-gray-300  sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                            placeholder="Nombre" required>
                            <option value="">Seleccione un usuario</option>
                            <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
                        </select>
                        <span class="text-red-500" v-if="form.errors['user_id']">{{ form.errors['user_id'] }}</span>
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <label for="first-name" class="block mb-2 text-sm font-medium  dark:text-black">Titulo</label>
                        <input type="text" v-model="form.title"
                            class="shadow-sm bg-gray-50 border border-gray-300  sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                            placeholder="Nombre" required>
                        <span class="text-red-500" v-if="form.errors['title']">{{ form.errors['title'] }}</span>
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <label for="first-name" class="block mb-2 text-sm font-medium  dark:text-black">Contenido</label>
                        <textarea type="text" v-model="form.body" cols="30" rows="10"
                            class="shadow-sm bg-gray-50 border border-gray-300  sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                            placeholder="Nombre" required>
                        </textarea>
                        <span class="text-red-500" v-if="form.errors['body']">{{ form.errors['body'] }}</span>
                    </div>
                </div>
            </form>
        </div>
        <div class="items-center p-6 border-t flex justify-around border-gray-200 rounded-b dark:border-gray-700">
            <button @click="submit"
                class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-primary-800"
                type="submit">{{ form.id === 0 ? 'Guardar' : 'Actualizar' }}</button>

            <Link :href="route('posts.index')"
                class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-primary-800"
                type="submit">Cancelar</Link>
        </div>
    </div>
</template>