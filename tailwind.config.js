const withMT = require("@material-tailwind/react/utils/withMT");
 
module.exports = withMT({  
  content: [
    "./assets/src/**/*.{js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {},
  },
  plugins: []
});