/* eslint-disable react/prop-types */

const InputTerminalBlock = ({ value, onChange, style, ...props }) => {
  const inputStyle = {
    whiteSpace: 'pre', // Preserve whitespace and line breaks
    fontFamily: 'monospace', // Use a monospaced font
    padding: '10px', // Add some padding for better visibility
    backgroundColor: '#0d1117', // Background color for the input block
    color: '#FFF', // Text color
    border: 'none', // Remove default border
    outline: 'none', // Remove outline on focus
    resize: 'none', // Disable textarea resizing
    minHeight: '130px', // Set minimum height
    fontSize: '14px', // Set font size
    width: '100%', // Take up full width
    boxSizing: 'border-box', // Ensure the box size includes border and padding
    lineHeight: '1.5', // Increase line spacing a little
    borderRadius: '0px 0px 0px 8px',
    height: '100%'
  };

  return (
    <textarea
      value={value}
      onChange={onChange}
      style={{ ...inputStyle, ...style }}
      {...props}
    />
  );
};

export default InputTerminalBlock;