export function partnerLogosDropzone(config) {
    return {
        items: config.items || [],
        available: config.available || [],
        uploadUrl: config.uploadUrl,
        attachUrl: config.attachUrl,
        itemUpdateUrl: config.itemUpdateUrl,
        itemDetachUrl: config.itemDetachUrl,
        csrf: config.csrf,
        dragging: false,
        uploading: false,
        progress: 0,
        error: '',
        message: '',
        selectedPartnerId: '',

        get attachable() {
            const attachedIds = new Set(this.items.map((item) => item.id));
            return this.available.filter((partner) => !attachedIds.has(partner.id));
        },

        handleFiles(fileList) {
            const files = Array.from(fileList || []).filter((file) => file.type.startsWith('image/'));
            if (!files.length) {
                this.error = 'Please choose image files.';
                return;
            }
            this.upload(files);
        },

        async upload(files) {
            this.uploading = true;
            this.progress = 8;
            this.error = '';
            this.message = '';

            const formData = new FormData();
            files.forEach((file) => formData.append('files[]', file));

            try {
                const response = await fetch(this.uploadUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': this.csrf,
                        Accept: 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: formData,
                });

                this.progress = 85;
                const data = await response.json().catch(() => ({}));

                if (!response.ok) {
                    const firstError = Object.values(data.errors || {})[0]?.[0];
                    throw new Error(data.message || firstError || 'Upload failed.');
                }

                (data.items || []).forEach((item) => {
                    this.items.push(item);
                    this.available.push(item);
                });
                this.message = data.message || 'Partner logos uploaded.';
                this.progress = 100;
            } catch (err) {
                this.error = err.message || 'Upload failed.';
            } finally {
                setTimeout(() => {
                    this.uploading = false;
                    this.progress = 0;
                }, 350);
            }
        },

        async attachSelected() {
            if (!this.selectedPartnerId) {
                this.error = 'Choose a partner to attach.';
                return;
            }

            this.error = '';
            this.message = '';

            try {
                const response = await fetch(this.attachUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': this.csrf,
                        Accept: 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({ partner_id: Number(this.selectedPartnerId) }),
                });
                const data = await response.json().catch(() => ({}));
                if (!response.ok) {
                    throw new Error(data.message || 'Could not attach partner.');
                }
                if (data.item && !this.items.some((item) => item.id === data.item.id)) {
                    this.items.push(data.item);
                }
                this.selectedPartnerId = '';
                this.message = data.message || 'Partner attached.';
            } catch (err) {
                this.error = err.message || 'Could not attach partner.';
            }
        },

        async saveName(item) {
            this.error = '';
            try {
                const response = await fetch(this.itemUpdateUrl.replace('__ID__', item.id), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': this.csrf,
                        Accept: 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({
                        name: item.name,
                        _method: 'PATCH',
                    }),
                });
                const data = await response.json().catch(() => ({}));
                if (!response.ok) {
                    throw new Error(data.message || 'Could not save partner name.');
                }
                if (data.item) {
                    Object.assign(item, data.item);
                }
                this.message = data.message || 'Partner name saved.';
            } catch (err) {
                this.error = err.message || 'Could not save partner name.';
            }
        },

        async removeItem(item, index) {
            if (!confirm('Remove this partner from Partners on the day?')) {
                return;
            }
            this.error = '';
            try {
                const response = await fetch(this.itemDetachUrl.replace('__ID__', item.id), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': this.csrf,
                        Accept: 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({ _method: 'DELETE' }),
                });
                const data = await response.json().catch(() => ({}));
                if (!response.ok) {
                    throw new Error(data.message || 'Could not remove partner.');
                }
                this.items.splice(index, 1);
                this.message = data.message || 'Partner removed.';
            } catch (err) {
                this.error = err.message || 'Could not remove partner.';
            }
        },
    };
}
