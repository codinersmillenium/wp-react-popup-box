import { Input, Textarea } from "@material-tailwind/react";

const FormInputs = ({ register, errors }) => {
  return (
    <>
      <div>
        <Input
          type="text"
          {...register("popup_name", { required: "Nama Popup Box Wajib Diisi.." })}
          placeholder="Masukkan nama popup box..."
          size="lg"
          label="Nama Popup Box"
        />
        {errors.popup_name && <p className="text-red-500 text-sm">{errors.popup_name.message}</p>}
      </div>
      <div>
        <Input
          type="text"
          {...register("popup_title", { required: "Judul Popup Box Wajib Diisi..." })}
          size="lg"
          label="Judul Popup Box"
          placeholder="Masukkan judul popup box..."
        />
        {errors.popup_title && <p className="text-red-500 text-sm">{errors.popup_title.message}</p>}
      </div>
      <div>
        <Textarea
          {...register("popup_desc", { required: "Deskripsi Wajib Diisi..." })}
          size="lg"
          placeholder="Masukkan isi deskripsi pada popup box..."
        />
        {errors.popup_desc && <p className="text-red-500 text-sm">{errors.popup_desc.message}</p>}
      </div>
    </>
  );
};

export default FormInputs;