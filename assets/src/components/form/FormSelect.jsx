import { Select, Option, Chip } from "@material-tailwind/react";

const FormSelect = ({ register, errors, availableOptions, selected, setValue, getValues, handleSelectChange, handleRemove }) => {
  return (
    <>
      <div>
        <Select
          {...register("popup_type", { required: "Jenis Popup Box Wajib Diisi..." })}
          label="Jenis Popup Box"
          value={getValues("popup_type")}
          onChange={(value) => setValue("popup_type", value)}
        >
          <Option value="1">Toast Notifikasi</Option>
          <Option value="2">Modal Popup</Option>
        </Select>
        {errors.popup_type && <p className="text-red-500 text-sm">{errors.popup_type.message}</p>}
      </div>

      <div className="mt-2">
        <label className="block text-gray-700">Pilih halaman yang ingin ditampilkan</label>
        <Select {...register("popup_pages", { required: "Pilih minimal satu halaman..." })} onChange={handleSelectChange}
        >
          {availableOptions.map((option) => (
            <Option key={option.value} value={option.value}>
              {option.label}
            </Option>
          ))}
        </Select>
      </div>

      <div className="flex flex-wrap gap-2 mt-4">
        {selected.map((option) => (
          <Chip key={option.value} value={option.label} onClose={() => handleRemove(option.value)} color="blue" />
        ))}
      </div>
      {errors.popup_pages && <p className="text-red-500 text-sm">{errors.popup_pages.message}</p>}
    </>
  );
};

export default FormSelect;