# CMAKE generated file: DO NOT EDIT!
# Generated by "Unix Makefiles" Generator, CMake Version 3.6

# Delete rule output on recipe failure.
.DELETE_ON_ERROR:


#=============================================================================
# Special targets provided by cmake.

# Disable implicit rules so canonical targets will work.
.SUFFIXES:


# Remove some rules from gmake that .SUFFIXES does not remove.
SUFFIXES =

.SUFFIXES: .hpux_make_needs_suffix_list


# Suppress display of executed commands.
$(VERBOSE).SILENT:


# A target that is always out of date.
cmake_force:

.PHONY : cmake_force

#=============================================================================
# Set environment variables for the build.

# The shell in which to execute make rules.
SHELL = /bin/sh

# The CMake executable.
CMAKE_COMMAND = /home/d-star/clion-2016.3.1/bin/cmake/bin/cmake

# The command to remove a file.
RM = /home/d-star/clion-2016.3.1/bin/cmake/bin/cmake -E remove -f

# Escaping for special characters.
EQUALS = =

# The top-level source directory on which CMake was run.
CMAKE_SOURCE_DIR = /var/www/html/judger/core/judged

# The top-level build directory on which CMake was run.
CMAKE_BINARY_DIR = /var/www/html/judger/core/judged/cmake-build-debug

# Include any dependencies generated for this target.
include CMakeFiles/judged.dir/depend.make

# Include the progress variables for this target.
include CMakeFiles/judged.dir/progress.make

# Include the compile flags for this target's objects.
include CMakeFiles/judged.dir/flags.make

CMakeFiles/judged.dir/judged.cc.o: CMakeFiles/judged.dir/flags.make
CMakeFiles/judged.dir/judged.cc.o: ../judged.cc
	@$(CMAKE_COMMAND) -E cmake_echo_color --switch=$(COLOR) --green --progress-dir=/var/www/html/judger/core/judged/cmake-build-debug/CMakeFiles --progress-num=$(CMAKE_PROGRESS_1) "Building CXX object CMakeFiles/judged.dir/judged.cc.o"
	/usr/bin/c++   $(CXX_DEFINES) $(CXX_INCLUDES) $(CXX_FLAGS) -o CMakeFiles/judged.dir/judged.cc.o -c /var/www/html/judger/core/judged/judged.cc

CMakeFiles/judged.dir/judged.cc.i: cmake_force
	@$(CMAKE_COMMAND) -E cmake_echo_color --switch=$(COLOR) --green "Preprocessing CXX source to CMakeFiles/judged.dir/judged.cc.i"
	/usr/bin/c++  $(CXX_DEFINES) $(CXX_INCLUDES) $(CXX_FLAGS) -E /var/www/html/judger/core/judged/judged.cc > CMakeFiles/judged.dir/judged.cc.i

CMakeFiles/judged.dir/judged.cc.s: cmake_force
	@$(CMAKE_COMMAND) -E cmake_echo_color --switch=$(COLOR) --green "Compiling CXX source to assembly CMakeFiles/judged.dir/judged.cc.s"
	/usr/bin/c++  $(CXX_DEFINES) $(CXX_INCLUDES) $(CXX_FLAGS) -S /var/www/html/judger/core/judged/judged.cc -o CMakeFiles/judged.dir/judged.cc.s

CMakeFiles/judged.dir/judged.cc.o.requires:

.PHONY : CMakeFiles/judged.dir/judged.cc.o.requires

CMakeFiles/judged.dir/judged.cc.o.provides: CMakeFiles/judged.dir/judged.cc.o.requires
	$(MAKE) -f CMakeFiles/judged.dir/build.make CMakeFiles/judged.dir/judged.cc.o.provides.build
.PHONY : CMakeFiles/judged.dir/judged.cc.o.provides

CMakeFiles/judged.dir/judged.cc.o.provides.build: CMakeFiles/judged.dir/judged.cc.o


# Object files for target judged
judged_OBJECTS = \
"CMakeFiles/judged.dir/judged.cc.o"

# External object files for target judged
judged_EXTERNAL_OBJECTS =

judged: CMakeFiles/judged.dir/judged.cc.o
judged: CMakeFiles/judged.dir/build.make
judged: CMakeFiles/judged.dir/link.txt
	@$(CMAKE_COMMAND) -E cmake_echo_color --switch=$(COLOR) --green --bold --progress-dir=/var/www/html/judger/core/judged/cmake-build-debug/CMakeFiles --progress-num=$(CMAKE_PROGRESS_2) "Linking CXX executable judged"
	$(CMAKE_COMMAND) -E cmake_link_script CMakeFiles/judged.dir/link.txt --verbose=$(VERBOSE)

# Rule to build all files generated by this target.
CMakeFiles/judged.dir/build: judged

.PHONY : CMakeFiles/judged.dir/build

CMakeFiles/judged.dir/requires: CMakeFiles/judged.dir/judged.cc.o.requires

.PHONY : CMakeFiles/judged.dir/requires

CMakeFiles/judged.dir/clean:
	$(CMAKE_COMMAND) -P CMakeFiles/judged.dir/cmake_clean.cmake
.PHONY : CMakeFiles/judged.dir/clean

CMakeFiles/judged.dir/depend:
	cd /var/www/html/judger/core/judged/cmake-build-debug && $(CMAKE_COMMAND) -E cmake_depends "Unix Makefiles" /var/www/html/judger/core/judged /var/www/html/judger/core/judged /var/www/html/judger/core/judged/cmake-build-debug /var/www/html/judger/core/judged/cmake-build-debug /var/www/html/judger/core/judged/cmake-build-debug/CMakeFiles/judged.dir/DependInfo.cmake --color=$(COLOR)
.PHONY : CMakeFiles/judged.dir/depend

