<template>
    <div>

        <div class="user-profile">
            <h2>Edit your profile</h2>
            <h4>{{ userInfo.email }}</h4>
            <div class="user-profile-wrapper">
                <div class="field-wrapper">
                    <label for="email">Name:</label>
                    <input 
                        type="text"
                        id="name"
                        v-model="userInfo.name"
                        placeholder="Your name"
                        :maxlength="maxlengthName"
                    />
                </div>
                <div class="field-wrapper">
                    <label for="password">New password:</label>
                    <input 
                        type="password" 
                        id="password" 
                        v-model="password" 
                        placeholder="Enter your password"
                        :maxlength="maxlengthPass"
                    />
                </div>
                <div class="field-wrapper">
                    <label for="password">Confirm new password:</label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        v-model="password_confirmation" 
                        placeholder="Repeat your password"
                        :maxlength="maxlengthPass"
                    />
                </div>
                <button @click="save">Save</button>
            </div>
        </div>

    </div>
</template>

<script>
import { useCoreStore } from '@/stores/core';
import { useAuthStore } from '@/stores/auth';
export default {
    name: 'Profile',
    setup() {
        const coreStore = useCoreStore();
        const authStore = useAuthStore();

        return { coreStore, authStore };
    },
    data() {
        return {
            userInfo: {},
            password: '',
            password_confirmation: '',
            maxlengthName: 100,
            maxlengthPass: 30,
        };
    },
    mounted() {
        this.userInfo = JSON.parse(JSON.stringify(this.coreStore.user));
    },
    methods: {
        validateForm() {
            // Check if the name is empty or too short
            const trimmedName = this.userInfo.name.trim();
            if (!trimmedName) {
                // console.log('Name cannot be empty.');
                this.$showToast(null, 'Name cannot be empty.', 'error', null, 'bottom right');
                return false;
            }
            if (trimmedName.length < 3) {
                // console.log('Name must be at least 3 characters long.');
                this.$showToast(null, 'Name must be at least 3 characters long.', 'error', null, 'bottom right');
                return false;
            }
            // Check if the name exceeds max characters
            if (trimmedName.length > this.maxlengthName) {
                this.$showToast(null, `Name must be at most ${this.maxlengthName} characters long.`, 'error', null, 'bottom right');
                return false;
            }

            // Check if the name has changed
            if (trimmedName === this.coreStore.user.name && !this.password && !this.password_confirmation) {
                // console.log('No changes to save.');
                this.$showToast(null, 'No changes to save.', 'warning', null, 'bottom right');
                return false;
            }

            // Check if passwords match (only if either password is provided)
            if (this.password || this.password_confirmation) {
                if (this.password !== this.password_confirmation) {
                    // console.log('Passwords do not match.');
                    this.$showToast(null, 'Passwords do not match.', 'error', null, 'bottom right');
                    return false;
                }

                // Check if the password is too short
                if (this.password.length < 4) {
                    // console.log('Password must be at least 4 characters long.');
                    this.$showToast(null, 'Password must be at least 4 characters long.', 'error', null, 'bottom right');
                    return false;
                }

                // Check if the password exceeds max characters
                if (this.password.length > this.maxlengthPass) {
                    this.$showToast(null, `Password must be at most ${this.maxlengthPass} characters long.`, 'error', null, 'bottom right');
                    return false;
                }
            }

            return true;
        },
        save() {
            let vm = this;
            if (!vm.validateForm()) {
                return;
            }

            const updatedUser = { 
                name: vm.userInfo.name.trim(), 
                password: vm.password || undefined,  // Only send password if provided
                password_confirmation: vm.password_confirmation || undefined,
            };
            vm.coreStore.isLoading = true;
            vm.coreStore.updateUser(updatedUser)
                .then(response => {
                    vm.$showToast('Success', 'Profile edited', 'success', 'check', 'bottom right');
                })
                .catch(error => {
                    console.log('Error updating user info:', error);
                    vm.$showToast('Error', `There was a problem saving`, 'error', 'times', 'bottom right');
                })
                .finally(() => {
                    vm.coreStore.isLoading = false;
                });
        },
    },
};
</script>
<style scoped>
.user-profile{
    background: #fff;
    box-shadow: 1px 1px 3px 0 rgba(0, 0, 0, .4);
    padding: 30px;
    border-radius: 8px;
    max-width: 700px;
    margin: 0 auto;
}
.user-profile h2{
    margin: 0;
}
.user-profile h4{
    margin: 0;
    font-weight: 300;
    font-style: italic;
    font-size: 14px;
    color: #bbb;
}
.user-profile-wrapper{
    margin-top: 30px;
}
</style>