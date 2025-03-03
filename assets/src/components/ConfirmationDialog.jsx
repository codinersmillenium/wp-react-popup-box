import { Dialog, DialogHeader, DialogBody, DialogFooter, Button } from "@material-tailwind/react";

const ConfirmationDialog = ({ open, handleOpen, handleConfirm, field }) => {
  return (
    <Dialog open={open} handler={handleOpen}>
      <DialogHeader>{ field.header }</DialogHeader>
      <DialogBody>{ field.body }</DialogBody>
      <DialogFooter>
        <Button variant="text" color="gray" onClick={handleOpen}>
          Tidak
        </Button>
        <Button color="red" onClick={handleConfirm}>
          Ya
        </Button>
      </DialogFooter>
    </Dialog>
  );
};

export default ConfirmationDialog;