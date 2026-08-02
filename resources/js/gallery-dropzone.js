export function galleryDropzone(config) {
    return {
        items: config.items || [],
        uploadUrl: config.uploadUrl,
        itemUpdateUrl: config.itemUpdateUrl,
        itemDeleteUrl: config.itemDeleteUrl,
        csrf: config.csrf,
        dragging: false,
        uploading: false,
        progress: 0,
        error: '',
        message: '',

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

                (data.items || []).forEach((item) => this.items.push(item));
                this.message = data.message || 'Images uploaded.';
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

        async saveCaption(item) {
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
                        caption: item.caption,
                        _method: 'PATCH',
                    }),
                });
                const data = await response.json().catch(() => ({}));
                if (!response.ok) {
                    throw new Error(data.message || 'Could not save caption.');
                }
                this.message = data.message || 'Caption saved.';
            } catch (err) {
                this.error = err.message || 'Could not save caption.';
            }
        },

        async removeItem(item, index) {
            if (!confirm('Remove this image from the gallery?')) {
                return;
            }
            this.error = '';
            try {
                const response = await fetch(this.itemDeleteUrl.replace('__ID__', item.id), {
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
                    throw new Error(data.message || 'Could not remove image.');
                }
                this.items.splice(index, 1);
                this.message = data.message || 'Image removed.';
            } catch (err) {
                this.error = err.message || 'Could not remove image.';
            }
        },
    };
}
