import os
import re

# Supported comment patterns per file extension
COMMENT_PATTERNS = {
    '.php': {
        'single': [r'^\s*//', r'^\s*#'],
        'multi': [r'/\*', r'\*/']
    },
    '.js': {
        'single': [r'^\s*//'],
        'multi': [r'/\*', r'\*/']
    },
    '.css': {
        'multi': [r'/\*', r'\*/']
    },
    '.html': {
        'multi': [r'<!--', r'-->']
    }
}

def is_comment_line(line, file_ext, in_multiline):
    patterns = COMMENT_PATTERNS.get(file_ext, {})
    stripped = line.strip()

    # Detect start/end of multiline blocks
    if 'multi' in patterns:
        start_pat, end_pat = patterns['multi']
        if re.search(start_pat, stripped) and not in_multiline:
            return True, True  # Start of multiline
        elif re.search(end_pat, stripped) and in_multiline:
            return True, False  # End of multiline
        elif in_multiline:
            return True, True  # Inside multiline

    # Single-line comments
    if 'single' in patterns:
        for pattern in patterns['single']:
            if re.match(pattern, stripped):
                return True, in_multiline

    # Empty line
    if stripped == '':
        return True, in_multiline

    return False, in_multiline

def count_code_lines(path, exclude_dirs=None):
    total_lines = 0
    file_count = 0

    for root, dirs, files in os.walk(path):
        # Exclude directories
        if exclude_dirs:
            dirs[:] = [d for d in dirs if os.path.join(root, d) not in exclude_dirs]

        for file in files:
            file_ext = os.path.splitext(file)[1]
            if file_ext not in COMMENT_PATTERNS:
                continue

            file_path = os.path.join(root, file)
            in_multiline = False
            code_lines = 0

            try:
                with open(file_path, 'r', encoding='utf-8', errors='ignore') as f:
                    for line in f:
                        is_comment, in_multiline = is_comment_line(line, file_ext, in_multiline)
                        if not is_comment:
                            code_lines += 1
            except Exception as e:
                print(f"Failed to read {file_path}: {e}")
                continue

            print(f"{file_path}: {code_lines} code lines")
            total_lines += code_lines
            file_count += 1

    print("-" * 50)
    print(f"Scanned {file_count} files.")
    print(f"Total executable code lines: {total_lines}")

# Example usage
project_root = "C:\\xampp\\htdocs\\SWE2204-GROUP-2"

# Optional: Exclude E-Library subdirectories but allow top-level files
elibrary_subdirs = [os.path.join(project_root, "E-Library", d)
                    for d in os.listdir(os.path.join(project_root, "E-Library"))
                    if os.path.isdir(os.path.join(project_root, "E-Library", d))]

count_code_lines(project_root, exclude_dirs=elibrary_subdirs)
