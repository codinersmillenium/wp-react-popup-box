import { Button, Dialog, DialogHeader, DialogBody, DialogFooter } from "@material-tailwind/react";

const FormActions = ({ handleSubmit, onSubmit, loading, open, handleOpen, handleConfirm }) => {
  return (
    <div className="flex items-center justify-end gap-4 mt-4">
      <Button type="submit" variant="gradient" loading={loading}>
        Simpan
      </Button>
      <Dialog open={open} handler={handleOpen}>
        <DialogHeader>Konfirmasi Pembatalan</DialogHeader>
        <DialogBody>Apakah Anda yakin ingin membatalkan?</DialogBody>
        <DialogFooter>
          <Button variant="text" color="gray" onClick={handleOpen}>
            Tidak
          </Button>
          <Button color="red" onClick={handleConfirm}>
            Ya, Batalkan
          </Button>
        </DialogFooter>
      </Dialog>
      <Button variant="outlined" onClick={handleOpen}>
        Batal
      </Button>
    </div>
  );
};

export default FormActions;