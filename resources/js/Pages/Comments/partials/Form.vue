<script setup >
import { Link } from '@inertiajs/vue3';
import { defineProps, defineEmits } from 'vue';

const emit = defineEmits();

// props
defineProps({
    form: Object
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
                        <label for="first-name" class="block mb-2 text-sm font-medium  dark:text-black">Nombre</label>
                        <input type="text" v-model="form.name"
                            class="shadow-sm bg-gray-50 border border-gray-300  sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                            placeholder="Nombre" required>
                        <span class="text-red-500" v-if="form.errors['name']">{{ form.errors['name'] }}</span>
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <label for="first-name" class="block mb-2 text-sm font-medium  dark:text-black">Email</label>
                        <input type="text" v-model="form.email"
                            class="shadow-sm bg-gray-50 border border-gray-300  sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                            placeholder="Nombre" required>
                        <span class="text-red-500" v-if="form.errors['email']">{{ form.errors['email'] }}</span>
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <label for="first-name" class="block mb-2 text-sm font-medium  dark:text-black">Username</label>
                        <input type="text" v-model="form.username"
                            class="shadow-sm bg-gray-50 border border-gray-300  sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                            placeholder="Nombre" required>
                        <span class="text-red-500" v-if="form.errors['email']">{{ form.errors['email'] }}</span>
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <label for="last-name"
                            class="block mb-2 text-sm font-medium  dark:text-black">Sitio web</label>
                        <input type="text" maxlength="150" v-model="form.website"
                            class="shadow-sm bg-gray-50 border border-gray-300  sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 "
                            placeholder="..." required>
                        <span class="text-red-500" v-if="form.errors['website']">
                            {{ form.errors['website']}}
                        </span>
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <label for="last-name"
                            class="block mb-2 text-sm font-medium  dark:text-black">Dirección (Calle)</label>
                        <input type="text" maxlength="150" v-model="form.address.street"
                            class="shadow-sm bg-gray-50 border border-gray-300  sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 "
                            placeholder="..." required>
                        <span class="text-red-500" v-if="form.errors['address.street']">
                            {{ form.errors['address.street']}}
                        </span>
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <label for="last-name"
                            class="block mb-2 text-sm font-medium  dark:text-black">Dirección (Suite)</label>
                        <input type="text" maxlength="150" v-model="form.address.suite"
                            class="shadow-sm bg-gray-50 border border-gray-300  sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 "
                            placeholder="..." required>
                        <span class="text-red-500" v-if="form.errors['address.suite']">
                            {{ form.errors['address.suite']}}
                        </span>
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <label for="last-name"
                            class="block mb-2 text-sm font-medium  dark:text-black">Dirección (Cuidad)</label>
                        <input type="text" maxlength="150" v-model="form.address.city"
                            class="shadow-sm bg-gray-50 border border-gray-300  sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 "
                            placeholder="..." required>
                        <span class="text-red-500" v-if="form.errors['address.city']">
                            {{ form.errors['address.city']}}
                        </span>
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <label for="last-name"
                            class="block mb-2 text-sm font-medium  dark:text-black">Dirección (Zip-code)</label>
                        <input type="text" maxlength="150" v-model="form.address.zipcode"
                            class="shadow-sm bg-gray-50 border border-gray-300  sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 "
                            placeholder="..." required>
                        <span class="text-red-500" v-if="form.errors['address.zipcode']">
                            {{ form.errors['address.zipcode']}}
                        </span>
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <label for="last-name"
                            class="block mb-2 text-sm font-medium  dark:text-black">Dirección (Latitud)</label>
                        <input type="text" maxlength="150" v-model="form.address.geo.lat"
                            class="shadow-sm bg-gray-50 border border-gray-300  sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 "
                            placeholder="..." required>
                        <span class="text-red-500" v-if="form.errors['address.geo.lat']">
                            {{ form.errors['address.geo.lat']}}
                        </span>
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <label for="last-name"
                            class="block mb-2 text-sm font-medium  dark:text-black">Dirección (Longitud)</label>
                        <input type="text" maxlength="150" v-model="form.address.geo.lng"
                            class="shadow-sm bg-gray-50 border border-gray-300  sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 "
                            placeholder="..." required>
                        <span class="text-red-500" v-if="form.errors['address.geo.lng']">
                            {{ form.errors['address.geo.lng']}}
                        </span>
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <label for="last-name"
                            class="block mb-2 text-sm font-medium  dark:text-black">Teléfono</label>
                        <input type="text" maxlength="150" v-model="form.phone"
                            class="shadow-sm bg-gray-50 border border-gray-300  sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 "
                            placeholder="..." required>
                        <span class="text-red-500" v-if="form.errors['phone']">
                            {{ form.errors['phone']}}
                        </span>
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <label for="last-name"
                            class="block mb-2 text-sm font-medium  dark:text-black">Compañía (Nombre)</label>
                        <input type="text" maxlength="150" v-model="form.company.name"
                            class="shadow-sm bg-gray-50 border border-gray-300  sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 "
                            placeholder="..." required>
                        <span class="text-red-500" v-if="form.errors['company.name']">
                            {{ form.errors['company.name']}}
                        </span>
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <label for="last-name"
                            class="block mb-2 text-sm font-medium  dark:text-black">Compañía (Catch Phrase)</label>
                        <input type="text" maxlength="150" v-model="form.company.catchPhrase"
                            class="shadow-sm bg-gray-50 border border-gray-300  sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 "
                            placeholder="..." required>
                        <span class="text-red-500" v-if="form.errors['company.catchPhrase']">
                            {{ form.errors['company.catchPhrase']}}
                        </span>
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <label for="last-name"
                            class="block mb-2 text-sm font-medium  dark:text-black">Compañía (bs)</label>
                        <input type="text" maxlength="150" v-model="form.company.bs"
                            class="shadow-sm bg-gray-50 border border-gray-300  sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 "
                            placeholder="..." required>
                        <span class="text-red-500" v-if="form.errors['company.bs']">
                            {{ form.errors['company.bs']}}
                        </span>
                    </div>
                </div>
            </form>
        </div>
        <div class="items-center p-6 border-t flex justify-around border-gray-200 rounded-b dark:border-gray-700">
            <button @click="submit"
                class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-primary-800"
                type="submit">{{ form.id === 0 ? 'Guardar' : 'Actualizar' }}</button>

            <Link :href="route('users.index')"
                class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-primary-800"
                type="submit">Cancelar</Link>
        </div>
    </div>
</template>