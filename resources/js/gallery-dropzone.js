export function galleryDropzone(config) {
    return {
        items: config.items || [],
        albums: config.albums || [],
        currentGalleryId: Number(config.currentGalleryId),
        uploadUrl: config.uploadUrl,
        itemUpdateUrl: config.itemUpdateUrl,
        itemDeleteUrl: config.itemDeleteUrl,
        itemMoveUrl: config.itemMoveUrl,
        csrf: config.csrf,
        dragging: false,
        uploading: false,
        progress: 0,
        error: '',
        message: '',
        selectedIds: [],
        moveTargetId: '',

        toggleSelect(id) {
            const index = this.selectedIds.indexOf(id);
            if (index >= 0) {
                this.selectedIds.splice(index, 1);
            } else {
                this.selectedIds.push(id);
            }
        },

        selectAll() {
            this.selectedIds = this.items.map((item) => item.id);
        },

        albumTitle(id) {
            const album = this.albums.find((entry) => Number(entry.id) === Number(id));
            return album ? album.title : 'that album';
        },

        async patchItem(item, payload) {
            const response = await fetch(this.itemUpdateUrl.replace('__ID__', item.id), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.csrf,
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                    ...payload,
                    _method: 'PATCH',
                }),
            });
            const data = await response.json().catch(() => ({}));
            if (!response.ok) {
                throw new Error(data.message || 'Could not update photo.');
            }
            return data;
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
                    item.gallery_id = item.gallery_id || this.currentGalleryId;
                    this.items.push(item);
                });
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
                const data = await this.patchItem(item, { caption: item.caption });
                this.message = data.message || 'Caption saved.';
            } catch (err) {
                this.error = err.message || 'Could not save caption.';
            }
        },

        async moveItem(item, galleryId) {
            const targetId = Number(galleryId);
            if (!targetId || targetId === this.currentGalleryId) {
                return;
            }
            if (!confirm('Move this photo to ' + this.albumTitle(targetId) + '?')) {
                return;
            }
            this.error = '';
            try {
                const data = await this.patchItem(item, {
                    caption: item.caption,
                    gallery_id: targetId,
                });
                this.items = this.items.filter((entry) => entry.id !== item.id);
                this.selectedIds = this.selectedIds.filter((id) => id !== item.id);
                this.message = data.message || 'Photo moved.';
            } catch (err) {
                this.error = err.message || 'Could not move photo.';
            }
        },

        async moveSelected() {
            const targetId = Number(this.moveTargetId);
            if (!targetId || !this.selectedIds.length) {
                return;
            }
            if (targetId === this.currentGalleryId) {
                this.error = 'Choose a different album.';
                return;
            }
            if (!confirm('Move ' + this.selectedIds.length + ' photo(s) to ' + this.albumTitle(targetId) + '?')) {
                return;
            }
            this.error = '';
            try {
                const response = await fetch(this.itemMoveUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': this.csrf,
                        Accept: 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({
                        item_ids: this.selectedIds,
                        gallery_id: targetId,
                    }),
                });
                const data = await response.json().catch(() => ({}));
                if (!response.ok) {
                    throw new Error(data.message || 'Could not move photos.');
                }
                const moved = new Set((data.moved_ids || this.selectedIds).map(Number));
                this.items = this.items.filter((item) => !moved.has(item.id));
                this.selectedIds = [];
                this.moveTargetId = '';
                this.message = data.message || 'Photos moved.';
            } catch (err) {
                this.error = err.message || 'Could not move photos.';
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
                this.selectedIds = this.selectedIds.filter((id) => id !== item.id);
                this.message = data.message || 'Image removed.';
            } catch (err) {
                this.error = err.message || 'Could not remove image.';
            }
        },
    };
}
