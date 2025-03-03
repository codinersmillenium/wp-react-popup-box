import { CardHeader, Typography } from "@material-tailwind/react";

const FormHeader = () => {
  return (
    <CardHeader
      variant="gradient"
      color="gray"
      className="mb-4 grid h-24 place-items-center"
    >
      <Typography variant="h3" color="white">
        Popup Box
      </Typography>
    </CardHeader>
  );
};

export default FormHeader;